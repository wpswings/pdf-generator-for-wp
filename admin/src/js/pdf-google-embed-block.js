const { registerBlockType } = wp.blocks;
const { TextControl, PanelBody } = wp.components;
const { useState } = wp.element;

if ('on' === embed_block_param.is_google_active) {
    const getSlidesEmbedUrl = (url) => {
        if (!url) return '';

        if (url.includes('docs.google.com/presentation')) {
            let embedUrl = new URL(url);
            embedUrl.pathname = embedUrl.pathname.replace('/pub', '/embed');

            return embedUrl.toString();
        }
        if (url.includes('docs.google.com/document')) {
            return url.replace('/edit', '/preview');
        }
        if (url.includes('docs.google.com/spreadsheets')) {
            return url.replace('/edit', '/pubhtml');
        }
           
        if (url.includes('docs.google.com/forms')) {
            return url.replace('/viewform', '/viewform?embedded=true');
        }
            
        if (url.includes('www.google.com/maps')) {

            const match = url.match(/@([-.\d]+),([-.\d]+)/);
            let lat = '';
            let lng = '';
            if (match) {
                lat = match[1];
                lng = match[2];
            }
       
            // Extract the place name from the URL
            const placeMatch = url.match(/place\/([^/@]+)/);
            let place = placeMatch ? placeMatch[1].replace(/\+/g, ' ') : '';
       
            // Construct the embed URL
            return `https://maps.google.com/maps?hl=en&ie=UTF8&ll=${lat},${lng}&spn=${lat},${lng}&q=${place}&t=m&z=17&output=embed&iwloc`;
          
        }
        if (url.includes('calendar.google.com/calendar')) {
            return url;
        }
        if (url.includes('www.youtube.com/watch')) {
            return url.replace('watch?v=', 'embed/');
        }
        return url;
    };

    registerBlockType('wpswings/google-embed', {
        title: 'WPSwings Google Embed',
        icon: 'admin-site',
        category: 'embed',
        attributes: {
            url: { type: 'string', default: '' }
        },

        edit: ({ attributes, setAttributes }) => {
            const getEmbedUrl = (url) => {
                if (!url) return '';

                if (url.includes('docs.google.com/presentation')) {
                    let embedUrl = new URL(url);
                    embedUrl.pathname = embedUrl.pathname.replace('/pub', '/embed');

                    return embedUrl.toString();
                }
                if (url.includes('docs.google.com/document')) {
                    return url.replace('/edit', '/preview');
                }
                if (url.includes('docs.google.com/spreadsheets')) {
                    return url.replace('/edit', '/pubhtml');
                }
           
                if (url.includes('docs.google.com/forms')) {
                    return url.replace('/viewform', '/viewform?embedded=true');
                }
            
                if (url.includes('www.google.com/maps')) {
                    const match = url.match(/@([-.\d]+),([-.\d]+)/);
                    let lat = '';
                    let lng = '';
                    if (match) {
                        lat = match[1];
                        lng = match[2];
                    }
       
                    // Extract the place name from the URL
                    const placeMatch = url.match(/place\/([^/@]+)/);
                    let place = placeMatch ? placeMatch[1].replace(/\+/g, ' ') : '';
       
                    // Construct the embed URL
                    return `https://maps.google.com/maps?hl=en&ie=UTF8&ll=${lat},${lng}&spn=${lat},${lng}&q=${place}&t=m&z=17&output=embed&iwloc`;
          
                }
                if (url.includes('calendar.google.com/calendar')) {
                    return url;
                }
                if (url.includes('www.youtube.com/watch')) {
                    return url.replace('watch?v=', 'embed/');
                }
                return url;
            };

            return (
                wp.element.createElement('div', {},
                    wp.element.createElement(TextControl, {
                        label: 'Google Service Embed URL',
                        value: attributes.url,
                        onChange: (url) => setAttributes({ url }),
                        placeholder: 'Paste Google Docs, Sheets, Slides, Forms, etc. URL'
                    }),
                    attributes.url &&
                    (attributes.url.includes('docs.google.com/drawings')
                        ? wp.element.createElement('img', {
                            src: getEmbedUrl(attributes.url),
                            width: '800',
                            height: '500',
                            style: { border: '1px solid #ddd' }
                        })
                        : wp.element.createElement('iframe', {
                            src: getEmbedUrl(attributes.url),
                            width: '800',
                            height: '500',
                            allowFullScreen: true,
                            style: { border: '1px solid #ddd' }
                        })
                    )
                )
            );
        },

        save: ({ attributes }) => {
            return (attributes.url.includes('docs.google.com/drawings')
                ? wp.element.createElement('img', {
                    src: getSlidesEmbedUrl(attributes.url),
                    width: '800',
                    height: '500',
                    style: { border: '1px solid #ddd' }
                })
                : wp.element.createElement('iframe', {
                    src: getSlidesEmbedUrl(attributes.url),
                    width: '800',
                    height: '500',
                    allowFullScreen: true,
                    style: { border: '1px solid #ddd' }
                })
            )



        }
    });
}



const { useBlockProps } = wp.blockEditor;

registerBlockType('custom/pdf-shortcode', {
    title: 'WPSwings PDF Shortcode',
    icon: 'media-document',
    category: 'widgets',
    attributes: {
        shortcode: { type: 'string', default: '[WORDPRESS_PDF]' }
    },
    edit: function(props) {
        return wp.element.createElement('div', useBlockProps(),
            wp.element.createElement(TextControl, {
                label: 'Enter Shortcode',
                value: props.attributes.shortcode,
                onChange: function(shortcode) { props.setAttributes({ shortcode: shortcode }) },
                placeholder: '[WORDPRESS_PDF]'
            }),
            wp.element.createElement('p', {}, 'Shortcode Output: ' + props.attributes.shortcode)
        );
    },
    save: function(props) {
        return wp.element.createElement('div', useBlockProps.save(), props.attributes.shortcode);
    }
});



registerBlockType('custom/image-shortcode', {
    title: 'WPSwings Single Image',
    icon: 'format-image',
    category: 'widgets',
    attributes: {
        id: { type: 'string', default: 'Image ID' },
        width: { type: 'string', default: '50' },
        height: { type: 'string', default: '50' }
    },
    edit: function(props) {
        return wp.element.createElement('div', useBlockProps(),
            wp.element.createElement(TextControl, {
                label: 'Image ID',
                value: props.attributes.id,
                onChange: function(id) { props.setAttributes({ id: id }) },
                placeholder: 'Enter Image ID'
            }),
            wp.element.createElement(TextControl, {
                label: 'Width',
                value: props.attributes.width,
                onChange: function(width) { props.setAttributes({ width: width }) },
                placeholder: 'Enter Width (e.g., 50)'
            }),
            wp.element.createElement(TextControl, {
                label: 'Height',
                value: props.attributes.height,
                onChange: function(height) { props.setAttributes({ height: height }) },
                placeholder: 'Enter Height (e.g., 50)'
            }),
            wp.element.createElement('p', {}, `Shortcode Output: [WPS_SINGLE_IMAGE id="${props.attributes.id}" width="${props.attributes.width}" height="${props.attributes.height}"]`)
        );
    },
    save: function(props) {
        return wp.element.createElement('div', useBlockProps.save(), `[WPS_SINGLE_IMAGE id="${props.attributes.id}" width="${props.attributes.width}" height="${props.attributes.height}"]`);
    }
});


if ('on' === embed_block_param.is_calendly_active) {
(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { TextControl } = wp.components;
    const { useBlockProps } = wp.blockEditor;

    registerBlockType('custom/calendly-embed', {
        title: 'WPSwings Calendly Embed',
        icon: 'calendar-alt',
        category: 'widgets',
        attributes: {
            url: { type: 'string', default: 'https://calendly.com/princekumaryadav-wpswings/new-meeting-2' }
        },
        edit: function(props) {
            return wp.element.createElement('div', useBlockProps(),
                wp.element.createElement(wp.components.TextControl, {
                    label: 'Calendly URL',
                    value: props.attributes.url,
                    onChange: function(url) { props.setAttributes({ url: url }) },
                    placeholder: 'Enter Calendly URL'
                }),
                wp.element.createElement('p', {}, `Shortcode Output: [wps_calendly url="${props.attributes.url}"]`)
            );
        },
        save: function(props) {
            return wp.element.createElement('div', useBlockProps.save(), `[wps_calendly url="${props.attributes.url}"]`);
        }
    });
})(window.wp);
}

if ('on' === embed_block_param.is_twitch_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl, ToggleControl } = wp.components;
        const { useBlockProps } = wp.blockEditor;

        registerBlockType('custom/twitch-embed', {
            title: 'WPSwings Twitch Embed',
            icon: 'video-alt3',
            category: 'widgets',
            attributes: {
                channel: { type: 'string', default: '' },
                height: { type: 'string', default: '480' },
                width: { type: 'string', default: '100%' },
                chatHeight: { type: 'string', default: '480' },
                chatWidth: { type: 'string', default: '100%' },
                showChat: { type: 'boolean', default: true }
            },

            edit: function (props) {
                const { attributes, setAttributes } = props;

                return wp.element.createElement('div', useBlockProps(),
                    wp.element.createElement(TextControl, {
                        label: 'Twitch Channel Name',
                        value: attributes.channel,
                        onChange: (val) => setAttributes({ channel: val }),
                        placeholder: 'e.g., twitch_username'
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Player Width',
                        value: attributes.width,
                        onChange: (val) => setAttributes({ width: val }),
                        placeholder: 'e.g., 100%'
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Player Height',
                        value: attributes.height,
                        onChange: (val) => setAttributes({ height: val }),
                        placeholder: 'e.g., 480'
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Show Chat',
                        checked: attributes.showChat,
                        onChange: (val) => setAttributes({ showChat: val })
                    }),
                    attributes.showChat && wp.element.createElement(TextControl, {
                        label: 'Chat Width',
                        value: attributes.chatWidth,
                        onChange: (val) => setAttributes({ chatWidth: val }),
                        placeholder: 'e.g., 100%'
                    }),
                    attributes.showChat && wp.element.createElement(TextControl, {
                        label: 'Chat Height',
                        value: attributes.chatHeight,
                        onChange: (val) => setAttributes({ chatHeight: val }),
                        placeholder: 'e.g., 480'
                    }),
                    wp.element.createElement('p', {}, `[wps_twitch channel="${attributes.channel}" width="${attributes.width}" height="${attributes.height}" show_chat="${attributes.showChat ? 'yes' : 'no'}" chat_width="${attributes.chatWidth}" chat_height="${attributes.chatHeight}"]`)
                );
            },

            save: function (props) {
                const attrs = props.attributes;
                return wp.element.createElement('div', useBlockProps.save(),
                    `[wps_twitch channel="${attrs.channel}" width="${attrs.width}" height="${attrs.height}" show_chat="${attrs.showChat ? 'yes' : 'no'}" chat_width="${attrs.chatWidth}" chat_height="${attrs.chatHeight}"]`
                );
            }
        });
    })(window.wp);
}

if ('on' === embed_block_param.is_strava_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl } = wp.components;
        const { useBlockProps } = wp.blockEditor;

        registerBlockType('custom/strava-embed', {
            title: 'WPSwings Strava Embed',
            icon: 'location-alt',
            category: 'widgets',
            attributes: {
                id: { type: 'string', default: '' }
            },

            edit: function (props) {
                const { attributes, setAttributes } = props;

                return wp.element.createElement('div', useBlockProps(),
                    wp.element.createElement(TextControl, {
                        label: 'Strava Activity ID',
                        value: attributes.id,
                        onChange: (val) => setAttributes({ id: val }),
                        placeholder: 'e.g., 14077587098'
                    }),
                    wp.element.createElement('p', {}, `[wps_strava id="${attributes.id}"]`)
                );
            },

            save: function (props) {
                const a = props.attributes;
                return wp.element.createElement('div', useBlockProps.save(), `[wps_strava id="${a.id}"]`);
            }
        });
    })(window.wp);
}

if ('on' === embed_block_param.is_ai_chatbot_active && (embed_block_param.is_pro_active)) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl, ColorPicker } = wp.components;
        const { useBlockProps } = wp.blockEditor;
        const { Fragment } = wp.element;

        registerBlockType('custom/ai-chatbot-embed', {
            title: 'WPSwings AI Chatbot',
            icon: 'format-chat',
            category: 'widgets',
            attributes: {
                url: { type: 'string', default: '' },
                height: { type: 'string', default: '700px' },
                header_color: { type: 'string', default: '#4e54c8' },
                header_title: { type: 'string', default: 'AI Chat Assistant' }
            },

            edit: function (props) {
                const { attributes, setAttributes } = props;

                return wp.element.createElement(Fragment, {},
                    wp.element.createElement('div', useBlockProps(), [
                        wp.element.createElement(TextControl, {
                            label: 'Chatbot URL',
                            value: attributes.url,
                            onChange: (val) => setAttributes({ url: val }),
                            placeholder: 'https://your-chatbot-url.com'
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Chatbot Height (e.g. 700px)',
                            value: attributes.height,
                            onChange: (val) => setAttributes({ height: val })
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Header Title',
                            value: attributes.header_title,
                            onChange: (val) => setAttributes({ header_title: val }),
                            placeholder: 'AI Chat Assistant'
                        }),
                        wp.element.createElement('div', { style: { marginTop: '20px', marginBottom: '10px' } },
                            wp.element.createElement('strong', null, 'Header Background Color')
                        ),
                        wp.element.createElement(ColorPicker, {
                            color: attributes.header_color,
                            onChangeComplete: (value) => setAttributes({ header_color: value.hex }),
                            disableAlpha: true
                        }),
                        wp.element.createElement('div', { style: { marginTop: '20px' } },
                            wp.element.createElement('code', null,
                                `[wps_ai_chatbot url="${attributes.url}" height="${attributes.height}" header_color="${attributes.header_color}" header_title="${attributes.header_title}"]`
                            )
                        )
                    ])
                );
            },

            save: function (props) {
                const { url, height, header_color, header_title } = props.attributes;
                return wp.element.createElement('div', useBlockProps.save(), `[wps_ai_chatbot url="${url}" height="${height}" header_color="${header_color}" header_title="${header_title}"]`);
            }
        });
    })(window.wp);
}

if ('on' === embed_block_param.is_rss_feed_active  && (embed_block_param.is_pro_active)) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl, ColorPicker } = wp.components;
        const { useBlockProps } = wp.blockEditor;
        const { Fragment } = wp.element;

        registerBlockType('custom/rssapp-embed', {
            title: 'WPSwings RSS Feed Embed',
            icon: 'rss',
            category: 'widgets',
            attributes: {
                url: { type: 'string', default: '' },
                height: { type: 'string', default: '600px' },
                title: { type: 'string', default: '📰 Latest News' },
                bg_color: { type: 'string', default: '#ffffff' },
                text_color: { type: 'string', default: '#333333' },
                border_color: { type: 'string', default: '#eeeeee' }
            },

            edit: function (props) {
                const { attributes, setAttributes } = props;

                return wp.element.createElement(Fragment, {},
                    wp.element.createElement('div', useBlockProps(), [
                        wp.element.createElement(TextControl, {
                            label: 'RSS Widget URL',
                            value: attributes.url,
                            onChange: (val) => setAttributes({ url: val }),
                            placeholder: 'https://rss.app/embed/your-widget'
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Widget Height (e.g. 600px)',
                            value: attributes.height,
                            onChange: (val) => setAttributes({ height: val })
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Widget Title',
                            value: attributes.title,
                            onChange: (val) => setAttributes({ title: val })
                        }),

                        // Color pickers
                        wp.element.createElement('div', { style: { marginTop: '20px' } },
                            wp.element.createElement('strong', null, 'Background Color')
                        ),
                        wp.element.createElement(ColorPicker, {
                            color: attributes.bg_color,
                            onChangeComplete: (value) => setAttributes({ bg_color: value.hex }),
                            disableAlpha: true
                        }),

                        wp.element.createElement('div', { style: { marginTop: '20px' } },
                            wp.element.createElement('strong', null, 'Text Color')
                        ),
                        wp.element.createElement(ColorPicker, {
                            color: attributes.text_color,
                            onChangeComplete: (value) => setAttributes({ text_color: value.hex }),
                            disableAlpha: true
                        }),

                        wp.element.createElement('div', { style: { marginTop: '20px' } },
                            wp.element.createElement('strong', null, 'Border Color')
                        ),
                        wp.element.createElement(ColorPicker, {
                            color: attributes.border_color,
                            onChangeComplete: (value) => setAttributes({ border_color: value.hex }),
                            disableAlpha: true
                        }),

                        wp.element.createElement('div', { style: { marginTop: '20px' } },
                            wp.element.createElement('code', null,
                                `[wps_rssapp_feed url="${attributes.url}" height="${attributes.height}" title="${attributes.title}" bg_color="${attributes.bg_color}" text_color="${attributes.text_color}" border_color="${attributes.border_color}"]`
                            )
                        )
                    ])
                );
            },

            save: function (props) {
                const { url, height, title, bg_color, text_color, border_color } = props.attributes;
                return wp.element.createElement('div', useBlockProps.save(), `[wps_rssapp_feed url="${url}" height="${height}" title="${title}" bg_color="${bg_color}" text_color="${text_color}" border_color="${border_color}"]`);
            }
        });
    })(window.wp);
}


(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { InnerBlocks } = wp.blockEditor;

    registerBlockType('wpswings/embed-container', {
        title: 'WPSwings Embed Container',
        icon: 'screenoptions',
        category: 'wpswings-embeds',
        supports: {
            align: true,
        },

        edit: () => {
            return (
                wp.element.createElement('div', { className: 'wpswings-embed-container', style: { border: '1px solid #ddd', padding: '10px' } },
                    wp.element.createElement('h4', {}, 'Embed Multiple Items'),
                    wp.element.createElement(InnerBlocks, {
                        allowedBlocks: [
                            'wpswings/linkedin-embed',
                            'wpswings/canva-embed',
                            'wpswings/reddit-embed',
                            'wpswings/loom-embed',
                            'wpswings/x-embed',
                            'wpswings/pdf-embed',
                        ]
                    })
                )
            );
        },

        save: () => {
            return (
                wp.element.createElement('div', { className: 'wpswings-embed-container' },
                    wp.element.createElement(InnerBlocks.Content, {})
                )
            );
        }
    });
})(window.wp);

if ('on' === embed_block_param.is_linkedln_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl } = wp.components;
        const { useState } = wp.element;

        registerBlockType('wpswings/linkedin-embed', {
            title: 'WPSwings LinkedIn Embed',
            icon: wp.element.createElement('svg', {
                width: 24, height: 24, viewBox: "0 0 24 24", xmlns: "http://www.w3.org/2000/svg"
            },
                wp.element.createElement('path', {
                    d: "M4.98 3.5c0 1.38-1.12 2.5-2.5 2.5S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.02 8h4.96v14H.02V8zM7.98 8h4.76v1.9h.07c.66-1.26 2.27-2.6 4.68-2.6 5 0 5.92 3.3 5.92 7.58V22h-4.96v-7.6c0-1.81-.03-4.14-2.52-4.14-2.52 0-2.91 1.97-2.91 4V22H7.98V8z",
                    fill: "#0077B5"
                })),
            category: 'wpswings-embeds',
            attributes: {
                postId: { type: 'string', default: '' },
                redirectUrl: { type: 'string', default: 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation' }
            },

            edit: ({ attributes, setAttributes }) => {
                const [postId, setPostId] = useState(attributes.postId);

                const getEmbedUrl = (postId) => {
                    return `https://www.linkedin.com/embed/feed/update/urn:li:share:${postId}`;
                };

                return (
                    wp.element.createElement('div', {},
                        wp.element.createElement(TextControl, {
                            label: 'LinkedIn Post ID',
                            value: postId,
                            onChange: (value) => {
                                setPostId(value);
                                setAttributes({ postId: value });
                            },
                            placeholder: 'Enter LinkedIn Post ID (e.g., 7307328960277684224)'
                        }),
                        wp.element.createElement('p', { style: { marginTop: '10px' } },
                            'Need help? ',
                            wp.element.createElement('a', { href: attributes.redirectUrl, target: '_blank', rel: 'noopener noreferrer', style: { color: '#1DA1F2' } }, 'How to use this block?')
                        ),
                        postId &&
                        wp.element.createElement('iframe', {
                            src: getEmbedUrl(postId),
                            width: '600',
                            height: '400',
                            allowFullScreen: true,
                            style: { border: '1px solid #ddd' }
                        }),
                    )
                );
            },

            save: ({ attributes }) => {
                return attributes.postId ? (
                    wp.element.createElement('iframe', {
                        src: `https://www.linkedin.com/embed/feed/update/urn:li:share:${attributes.postId}`,
                        width: '600',
                        height: '400',
                        allowFullScreen: true,
                        style: { border: '1px solid #ddd' }
                    })
                ) : null;
            }
        });
    })(window.wp);
}


/*Canva Embedding */
if ('on' === embed_block_param.is_canva_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl } = wp.components;
        const { useState } = wp.element;

        registerBlockType('wpswings/canva-embed', {
            title: 'WPSwings Canva Embed',
            icon: (
                wp.element.createElement('svg', { width: 24, height: 24, viewBox: '0 0 24 24' },
                    wp.element.createElement('path', {
                        d: 'M12 0C5.372 0 0 5.372 0 12s5.372 12 12 12 12-5.372 12-12S18.628 0 12 0zm2.5 18.5c-1.1 0-1.9-.4-2.5-1.2-.6.8-1.4 1.2-2.5 1.2-1.8 0-3-1.4-3-3.5 0-3.1 3-5.5 6-5.5s6 2.4 6 5.5c0 2.1-1.2 3.5-3 3.5z',
                        fill: '#00C4CC'
                    })
                )
            ),
            category: 'wpswings-embeds',
            attributes: {
                canvaUrl: { type: 'string', default: 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation' },
            },

            edit: ({ attributes, setAttributes }) => {
                const [canvaUrl, setCanvaUrl] = useState(attributes.canvaUrl);

                return (
                    wp.element.createElement('div', {},
                        wp.element.createElement(TextControl, {
                            label: 'Canva Design URL',
                            value: canvaUrl,
                            onChange: (value) => {
                                setCanvaUrl(value);
                                setAttributes({ canvaUrl: value });
                            },
                            placeholder: 'Enter Canva share link (e.g., https://www.canva.com/design/your-design-id/watch)'
                        }),
                        wp.element.createElement('p', { style: { marginTop: '10px' } },
                            'Need help? ',
                            wp.element.createElement('a', { href: attributes.redirectUrl, target: '_blank', rel: 'noopener noreferrer', style: { color: '#1DA1F2' } }, 'How to use this block?')
                        ),
                        canvaUrl &&
                        wp.element.createElement('div', {
                            style: {
                                position: 'relative',
                                width: '100%',
                                height: 0,
                                paddingTop: '56.25%',
                                overflow: 'hidden',
                                borderRadius: '8px',
                                boxShadow: '0 2px 8px rgba(63,69,81,0.16)',
                                marginTop: '1.6em',
                                marginBottom: '0.9em'
                            }
                        },
                            wp.element.createElement('iframe', {
                                src: canvaUrl + '?embed',
                                style: {
                                    position: 'absolute',
                                    width: '100%',
                                    height: '100%',
                                    top: 0,
                                    left: 0,
                                    border: 'none'
                                },
                                allowFullScreen: true
                            })
                        )
                    )
                );
            },

            save: ({ attributes }) => {
                return attributes.canvaUrl ? (
                    wp.element.createElement('div', {
                        style: {
                            position: 'relative',
                            width: '100%',
                            height: 0,
                            paddingTop: '56.25%',
                            overflow: 'hidden',
                            borderRadius: '8px',
                            boxShadow: '0 2px 8px rgba(63,69,81,0.16)',
                            marginTop: '1.6em',
                            marginBottom: '0.9em'
                        }
                    },
                        wp.element.createElement('iframe', {
                            src: attributes.canvaUrl + '?embed',
                            style: {
                                position: 'absolute',
                                width: '100%',
                                height: '100%',
                                top: 0,
                                left: 0,
                                border: 'none'
                            },
                            allowFullScreen: true
                        })
                    )
                ) : null;
            }
        });
    })(window.wp);
}

/* Reddit Enbeeding */
if ('on' === embed_block_param.is_reddit_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl } = wp.components;
        const { useState } = wp.element;

        registerBlockType('wpswings/reddit-embed', {
            title: 'WPSwings Reddit Embed',
            icon: (
                wp.element.createElement('svg', { width: 24, height: 24, viewBox: '0 0 24 24' },
                    wp.element.createElement('path', { fill: '#FF4500', d: 'M12 0C5.372 0 0 5.372 0 12c0 5.309 3.438 9.8 8.207 11.385.6.111.818-.26.818-.577 0-.285-.011-1.04-.016-2.04-3.338.726-4.042-1.609-4.042-1.609-.546-1.386-1.333-1.756-1.333-1.756-1.089-.745.082-.73.082-.73 1.204.085 1.838 1.238 1.838 1.238 1.07 1.834 2.807 1.304 3.492.996.109-.775.419-1.304.762-1.604-2.665-.303-5.466-1.332-5.466-5.93 0-1.311.469-2.382 1.236-3.222-.124-.303-.536-1.523.116-3.176 0 0 1.008-.322 3.3 1.23.96-.267 1.986-.4 3.006-.404 1.02.004 2.046.137 3.008.404 2.29-1.552 3.296-1.23 3.296-1.23.654 1.653.242 2.873.118 3.176.77.84 1.234 1.911 1.234 3.222 0 4.612-2.804 5.624-5.472 5.921.43.372.814 1.105.814 2.226 0 1.607-.014 2.902-.014 3.298 0 .32.216.694.824.576C20.566 21.797 24 17.309 24 12c0-6.628-5.372-12-12-12z' })
                )
            ),
            category: 'wpswings-embeds',
            attributes: {
                redditUrl: { type: 'string', default: 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation' }
            },

            edit: ({ attributes, setAttributes }) => {
                const [redditUrl, setRedditUrl] = useState(attributes.redditUrl);

                return (
                    wp.element.createElement('div', {},
                        wp.element.createElement(TextControl, {
                            label: 'Reddit Post URL',
                            value: redditUrl,
                            onChange: (value) => {
                                setRedditUrl(value);
                                setAttributes({ redditUrl: value });
                            },
                            placeholder: 'Enter Reddit post URL'
                        }),
                        wp.element.createElement('p', { style: { marginTop: '10px' } },
                            'Need help? ',
                            wp.element.createElement('a', { href: attributes.redirectUrl, target: '_blank', rel: 'noopener noreferrer', style: { color: '#1DA1F2' } }, 'How to use this block?')
                        ),
                        redditUrl &&
                        wp.element.createElement('blockquote', {
                            className: 'reddit-embed-bq',
                            'data-embed-height': '316'
                        },
                            wp.element.createElement('a', { href: redditUrl }, 'View Reddit Post')
                        ),
                        wp.element.createElement('script', {
                            async: true,
                            src: 'https://embed.reddit.com/widgets.js',
                            charSet: 'UTF-8'
                        })
                    )
                );
            },

            save: ({ attributes }) => {
                return attributes.redditUrl ? (
                    wp.element.createElement('blockquote', {
                        className: 'reddit-embed-bq',
                        'data-embed-height': '316'
                    },
                        wp.element.createElement('a', { href: attributes.redditUrl }, 'View Reddit Post'),
                        wp.element.createElement('script', {
                            async: true,
                            src: 'https://embed.reddit.com/widgets.js',
                            charSet: 'UTF-8'
                        })
                    )
                ) : null;
            }
        });
    })(window.wp);
}


/* Embeed Loom */
if ('on' === embed_block_param.is_loom_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl } = wp.components;
        const { useState } = wp.element;

        registerBlockType('wpswings/loom-embed', {
            title: 'WPSwings Loom Embed',
            icon: 'video-alt3',
            category: 'wpswings-embeds',
            attributes: {
                loomUrl: { type: 'string', default: 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation' }
            },

            edit: ({ attributes, setAttributes }) => {
                const [loomUrl, setLoomUrl] = useState(attributes.loomUrl);

                const getLoomEmbedUrl = (url) => {
                    const match = url.match(/loom\.com\/share\/([a-zA-Z0-9]+)/);
                    return match ? `https://www.loom.com/embed/${match[1]}` : '';
                };

                return (
                    wp.element.createElement('div', {},
                        wp.element.createElement(TextControl, {
                            label: 'Loom Video URL',
                            value: loomUrl,
                            onChange: (value) => {
                                setLoomUrl(value);
                                setAttributes({ loomUrl: value });
                            },
                            placeholder: 'Enter Loom share link (e.g., https://www.loom.com/share/your-video-id)'
                        }),
                        wp.element.createElement('p', { style: { marginTop: '10px' } },
                            'Need help? ',
                            wp.element.createElement('a', { href: attributes.redirectUrl, target: '_blank', rel: 'noopener noreferrer', style: { color: '#1DA1F2' } }, 'How to use this block?')
                        ),
                        loomUrl &&
                        wp.element.createElement('iframe', {
                            src: getLoomEmbedUrl(loomUrl),
                            width: '640',
                            height: '360',
                            allowFullScreen: true,
                            style: { border: '1px solid #ddd' }
                        })
                    )
                );
            },

            save: ({ attributes }) => {
                const embedUrl = attributes.loomUrl ? attributes.loomUrl.replace('/share/', '/embed/') : '';
                return attributes.loomUrl ? (
                    wp.element.createElement('iframe', {
                        src: embedUrl,
                        width: '640',
                        height: '360',
                        allowFullScreen: true,
                        style: { border: '1px solid #ddd' }
                    })
                ) : null;
            }
        });
    })(window.wp);
}

/* x POST */
if ('on' === embed_block_param.is_x_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { TextControl } = wp.components;
        const { useEffect } = wp.element;

        registerBlockType('wpswings/x-embed', {
            title: 'WPSwings X (Twitter) Embed',
            icon: wp.element.createElement('svg', {
                width: 24, height: 24, viewBox: "0 0 24 24", xmlns: "http://www.w3.org/2000/svg"
            },
                wp.element.createElement('path', {
                    d: "M19.633 7.997c.013.176.013.353.013.53 0 5.404-4.114 11.63-11.63 11.63-2.32 0-4.48-.678-6.308-1.842a8.22 8.22 0 0 0 6.048-1.69c-1.792-.03-3.307-1.215-3.83-2.84a4.108 4.108 0 0 0 1.862-.07c-1.892-.385-3.315-2.06-3.315-4.07v-.05a4.07 4.07 0 0 0 1.842.515c-1.077-.718-1.792-1.95-1.792-3.34 0-.738.197-1.427.54-2.02a11.57 11.57 0 0 0 8.394 4.26c-.058-.295-.086-.603-.086-.91 0-2.24 1.793-4.07 4.07-4.07 1.17 0 2.225.492 2.967 1.28a8.059 8.059 0 0 0 2.56-.974 4.048 4.048 0 0 1-1.793 2.24 8.08 8.08 0 0 0 2.315-.616 8.49 8.49 0 0 1-2.03 2.1z",
                    fill: "#1DA1F2"
                })),
            category: 'wpswings-embeds',
            attributes: {
                postUrl: { type: 'string', default: '' },
                redirectUrl: { type: 'string', default: 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation' }
            },

            edit: ({ attributes, setAttributes }) => {
                useEffect(() => {
                    // Load Twitter widgets script if not already loaded
                    if (!window.twttr) {
                        const script = document.createElement('script');
                        script.src = "https://platform.twitter.com/widgets.js";
                        script.async = true;
                        document.body.appendChild(script);
                    } else {
                        window.twttr.widgets.load();
                    }
                }, [attributes.postUrl]);

                return (
                    wp.element.createElement('div', { style: { padding: '10px', border: '1px solid #ddd', borderRadius: '8px' } },
                        wp.element.createElement(TextControl, {
                            label: 'X (Twitter) Post URL',
                            value: attributes.postUrl,
                            onChange: (value) => setAttributes({ postUrl: value }),
                            placeholder: 'Enter X (Twitter) post URL (e.g., https://twitter.com/user/status/123456789)'
                        }),
                        wp.element.createElement('p', { style: { marginTop: '10px' } },
                            'Need help? ',
                            wp.element.createElement('a', { href: attributes.redirectUrl, target: '_blank', rel: 'noopener noreferrer', style: { color: '#1DA1F2' } }, 'How to use this block?')
                        ),
                        attributes.postUrl &&
                        wp.element.createElement('blockquote', { className: 'twitter-tweet' },
                            wp.element.createElement('a', { href: attributes.postUrl }, 'View Tweet')
                        )
                    )
                );
            },

            save: ({ attributes }) => {
                return attributes.postUrl ? (
                    wp.element.createElement('div', {},
                        wp.element.createElement('blockquote', { className: 'twitter-tweet' },
                            wp.element.createElement('a', { href: attributes.postUrl }, 'View Tweet')
                        ),
                        wp.element.createElement('script', {
                            async: true,
                            src: "https://platform.twitter.com/widgets.js",
                            charset: "utf-8"
                        })
                    )
                ) : null;
            }
        });
    })(window.wp);
}
 
/* PDF Embed */
if ('on' === embed_block_param.is_view_pdf_active) {
    (function (wp) {
        const { registerBlockType } = wp.blocks;
        const { MediaUpload, MediaUploadCheck } = wp.blockEditor;
        const { Button } = wp.components;
        const { useState } = wp.element;
   
        if (embed_block_param.is_pro_active) {
            registerBlockType('wpswings/pdf-embed', {
                title: 'WPS PDF Embed',
                icon: 'book',
                category: 'wpswings-embeds',
                attributes: {
                    pdfUrl: { type: 'string', default: 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation' }
                },

                edit: ({ attributes, setAttributes }) => {
                    const [preview, setPreview] = useState(attributes.pdfUrl);

                    return (
                        wp.element.createElement('div', { className: 'wps-pdf-upload' },
                            wp.element.createElement(MediaUploadCheck, {},
                                wp.element.createElement(MediaUpload, {
                                    allowedTypes: ['application/pdf'],
                                    value: attributes.pdfUrl,
                                    onSelect: (media) => {
                                        setAttributes({ pdfUrl: media.url });
                                        setPreview(media.url);
                                    },
                                    render: ({ open }) => (
                                        wp.element.createElement(Button, {
                                            onClick: open,
                                            isPrimary: true
                                        }, 'Upload PDF')
                                    )
                                })
                            ),
                            preview && wp.element.createElement('iframe', {
                                src: preview,
                                width: '100%',
                                height: '500px',
                                style: { border: '1px solid #ddd', marginTop: '10px' }
                            })
                        )
                    );
                },

                save: ({ attributes }) => {
                    return attributes.pdfUrl ? (
                        wp.element.createElement('div', {},
                            wp.element.createElement('iframe', {
                                src: attributes.pdfUrl,
                                width: '100%',
                                height: '500px',
                                style: { border: '1px solid #ddd' }
                            })
                        )
                    ) : null;
                }
            });
        }
    })(window.wp);
}