( function ( wp ) {
	if ( ! wp || ! wp.element || ! wp.apiFetch ) {
		return;
	}

	const dataEl = document.getElementById( 'pgfw-tabs-data' );
	const containerEl = document.getElementById( 'pgfw-react-app' );
	const contentEl = document.getElementById( 'pgfw-tab-content' );

	if ( ! dataEl || ! containerEl || ! contentEl ) {
		return;
	}

	const settings = JSON.parse( dataEl.textContent || '{}' );
	const initialHtml = contentEl.innerHTML;
	const { createElement, useEffect, useState } = wp.element;
	const createRoot = wp.element.createRoot || wp.element.render;

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

	const fetchTab = async ( tab ) => {
		const url = `${ settings.restUrl }?tab=${ encodeURIComponent( tab ) }`;
		return wp.apiFetch( {
			url,
			method: 'GET',
			headers: { 'X-WP-Nonce': settings.nonce },
		} );
	};

	const TabApp = () => {
		const [ activeTab, setActiveTab ] = useState( settings.activeTab );
		const [ html, setHtml ] = useState( initialHtml );
		const [ loading, setLoading ] = useState( false );

		useEffect( () => {
			if ( contentEl ) {
				contentEl.innerHTML = html;
				runScripts( contentEl );
			}
		}, [ html ] );

		useEffect( () => {
			document.body.classList.add( 'pgfw-react-active' );
		}, [] );

		useEffect( () => {
			const handlePop = () => {
				const params = new URLSearchParams( window.location.search );
				const tab = params.get( 'pgfw_tab' ) || settings.activeTab;
				if ( tab !== activeTab ) {
					loadTab( tab, false );
				}
			};
			window.addEventListener( 'popstate', handlePop );
			return () => window.removeEventListener( 'popstate', handlePop );
		} );

		const loadTab = async ( tab, push = true ) => {
			if ( loading || ! tab || tab === activeTab ) {
				return;
			}
			setLoading( true );
			try {
				const res = await fetchTab( tab );
				if ( res && res.html ) {
					setHtml( res.html );
					setActiveTab( tab );
					if ( window.pgfwInitUI ) {
						// Give DOM a tick to paint then init UI.
						setTimeout( () => window.pgfwInitUI(), 0 );
					}
					if ( push ) {
						const newUrl = `${ settings.pageUrl }&pgfw_tab=${ tab }`;
						window.history.pushState( { tab }, '', newUrl );
					}
				} else {
					window.location.href = `${ settings.pageUrl }&pgfw_tab=${ tab }`;
				}
			} catch ( e ) {
				// fallback to full load.
				window.location.href = `${ settings.pageUrl }&pgfw_tab=${ tab }`;
			} finally {
				setLoading( false );
			}
		};

		const renderTabButton = ( tab ) =>
			createElement(
				'button',
				{
					key: tab.key,
					className:
						'pgfw-tab-link' +
						( tab.key === activeTab ? ' is-active' : '' ),
					onClick: () => loadTab( tab.key, true ),
					type: 'button',
				},
				tab.title,
				tab.isPro
					? createElement(
							'span',
							{ className: 'pgfw-pill' },
							'PRO'
					  )
					: null
			);

		return createElement(
			'div',
			{ className: 'pgfw-react-nav' },
			createElement(
				'div',
				{ className: 'pgfw-nav' },
				createElement(
					'div',
					{ className: 'pgfw-nav__list' },
					settings.tabs.map( renderTabButton )
				)
			),
			loading &&
				createElement(
					'div',
					{ className: 'pgfw-loader' },
					createElement( 'span', null, 'Loading…' )
				)
		);
	};

	const app = createElement( TabApp );

	if ( typeof wp.element.createRoot === 'function' ) {
		wp.element.createRoot( containerEl ).render( app );
	} else {
		wp.element.render( app, containerEl );
	}
} )( window.wp );
