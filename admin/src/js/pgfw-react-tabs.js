( function ( wp ) {
	if ( ! wp || ! wp.apiFetch ) {
		return;
	}

	const dataEl = document.getElementById( 'pgfw-tabs-data' );
	const contentEl = document.getElementById( 'pgfw-tab-content' );
	const bodyGridEl = document.getElementById( 'pgfw-body-grid' );
	const heroCardEl = document.getElementById( 'pgfw-hero-card' );
	const heroEyebrowEl = document.getElementById( 'pgfw-hero-eyebrow' );
	const heroTitleEl = document.getElementById( 'pgfw-hero-title' );
	const heroSubEl = document.getElementById( 'pgfw-hero-sub' );

	if ( ! dataEl || ! contentEl ) {
		return;
	}

	return;

	const settings = JSON.parse( dataEl.textContent || '{}' );
	let activeTab = settings.activeTab;
	let isLoading = false;
	let loaderEl = document.querySelector( '.pgfw-loader' );

	if ( ! loaderEl ) {
		loaderEl = document.createElement( 'div' );
		loaderEl.className = 'pgfw-loader';
		loaderEl.hidden = true;
		loaderEl.innerHTML = '<span>Loading…</span>';
		document.body.appendChild( loaderEl );
	}

	const runScripts = ( host ) => {
		const scripts = host.querySelectorAll( 'script' );
		scripts.forEach( ( oldScript ) => {
			const newScript = document.createElement( 'script' );
			if ( oldScript.src ) {
				newScript.src = oldScript.src;
			} else {
				newScript.appendChild(
					document.createTextNode( oldScript.textContent )
				);
			}
			Array.from( oldScript.attributes ).forEach( ( attr ) => {
				newScript.setAttribute( attr.name, attr.value );
			} );
			oldScript.parentNode.replaceChild( newScript, oldScript );
		} );
	};

	const getActiveNavTab = ( tab ) =>
		( settings.parentTabs && settings.parentTabs[ tab ] ) || tab;

	const isOverviewTab = ( tab ) =>
		getActiveNavTab( tab ) === 'pdf-generator-for-wp-overview';

	const updateHeader = ( header ) => {
		if ( ! header ) {
			return;
		}

		if ( heroEyebrowEl && typeof header.eyebrow === 'string' ) {
			heroEyebrowEl.textContent = header.eyebrow;
		}

		if ( heroTitleEl && typeof header.title === 'string' ) {
			heroTitleEl.textContent = header.title;
		}

		if ( heroSubEl && typeof header.description === 'string' ) {
			heroSubEl.textContent = header.description;
		}
	};

	const updateShellState = ( tab, header ) => {
		const overviewTab = isOverviewTab( tab );

		if ( bodyGridEl ) {
			bodyGridEl.classList.toggle( 'pgfw-body-grid--overview', overviewTab );
		}

		if ( heroCardEl ) {
			heroCardEl.classList.toggle( 'pgfw-hidden', overviewTab );
		}

		if ( ! overviewTab ) {
			updateHeader( header );
		}
	};

	const clearActiveStates = () => {
		document
			.querySelectorAll( '.pgfw-legacy-nav li.is-active' )
			.forEach( ( item ) => item.classList.remove( 'is-active' ) );
	};

	const updateActiveStates = ( tab ) => {
		const activeNavTab = getActiveNavTab( tab );

		clearActiveStates();

		document
			.querySelectorAll( '.pgfw-legacy-nav a[data-tab]' )
			.forEach( ( link ) => {
				if ( link.dataset.tab !== activeNavTab ) {
					return;
				}

				const item = link.closest( 'li' );
				if ( item ) {
					item.classList.add( 'is-active' );
				}

				const moreItem = link.closest( '.pgfw-nav-more' );
				if ( moreItem ) {
					moreItem.classList.add( 'is-active' );
				}
			} );
	};

	const showLoader = () => {
		loaderEl.hidden = false;
	};

	const hideLoader = () => {
		loaderEl.hidden = true;
	};

	const fetchTab = async ( tab ) => {
		const url = `${ settings.restUrl }?tab=${ encodeURIComponent( tab ) }`;
		return wp.apiFetch( {
			url,
			method: 'GET',
			headers: { 'X-WP-Nonce': settings.nonce },
		} );
	};

	const parseTabFromUrl = ( href ) => {
		try {
			const url = new URL( href, window.location.origin );
			if ( url.searchParams.get( 'page' ) !== 'pdf_generator_for_wp_menu' ) {
				return null;
			}
			return url.searchParams.get( 'pgfw_tab' );
		} catch ( e ) {
			return null;
		}
	};

	const loadTab = async ( tab, push = true ) => {
		if ( isLoading || ! tab || tab === activeTab ) {
			return;
		}

		isLoading = true;
		showLoader();

		try {
			const res = await fetchTab( tab );
			if ( ! res || ! res.html ) {
				window.location.href = `${ settings.pageUrl }&pgfw_tab=${ tab }`;
				return;
			}

			contentEl.innerHTML = res.html;
			runScripts( contentEl );

			activeTab = tab;
			updateActiveStates( tab );
			updateShellState( tab, res.header || {} );

			if ( window.pgfwInitUI ) {
				setTimeout( () => window.pgfwInitUI(), 0 );
			}

			if ( push ) {
				window.history.pushState(
					{ tab },
					'',
					`${ settings.pageUrl }&pgfw_tab=${ tab }`
				);
			}
		} catch ( e ) {
			window.location.href = `${ settings.pageUrl }&pgfw_tab=${ tab }`;
		} finally {
			isLoading = false;
			hideLoader();
		}
	};

	document.addEventListener( 'click', ( event ) => {
		const link = event.target.closest( 'a[href]' );
		if ( ! link ) {
			return;
		}

		const tab = parseTabFromUrl( link.href );
		if ( ! tab ) {
			return;
		}

		event.preventDefault();
		loadTab( tab, true );
	} );

	window.addEventListener( 'popstate', () => {
		const params = new URLSearchParams( window.location.search );
		const tab = params.get( 'pgfw_tab' ) || settings.activeTab;
		if ( tab !== activeTab ) {
			loadTab( tab, false );
		}
	} );

	updateActiveStates( activeTab );
	updateShellState( activeTab, settings.header || {} );
} )( window.wp );
