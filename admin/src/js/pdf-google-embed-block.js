const { registerBlockType } = wp.blocks;
const { TextControl, PanelBody } = wp.components;
const { useState } = wp.element;
const getSlidesEmbedUrl = (url) => {
    if (!url) return '';

            if (url.includes('docs.google.com/presentation')) {
               // return url.replace('/pub', '/embed');
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
               // return url.replace('/maps/', '/maps/embed/');
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
               // return url.replace('/pub', '/embed');
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
               // return url.replace('/maps/', '/maps/embed/');
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
                    src:getEmbedUrl(attributes.url),
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
        width: { type: 'string', default: '10%' },
        height: { type: 'string', default: '10%' }
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
                placeholder: 'Enter Width (e.g., 10%)'
            }),
            wp.element.createElement(TextControl, {
                label: 'Height',
                value: props.attributes.height,
                onChange: function(height) { props.setAttributes({ height: height }) },
                placeholder: 'Enter Height (e.g., 10%)'
            }),
            wp.element.createElement('p', {}, `Shortcode Output: [WPS_SINGLE_IMAGE id="${props.attributes.id}" width="${props.attributes.width}" height="${props.attributes.height}"]`)
        );
    },
    save: function(props) {
        return wp.element.createElement('div', useBlockProps.save(), `[WPS_SINGLE_IMAGE id="${props.attributes.id}" width="${props.attributes.width}" height="${props.attributes.height}"]`);
    }
});



registerBlockType('custom/product-gallery-shortcode', {
    title: 'WPSwings Product Gallery',
    icon: 'images-alt2',
    category: 'widgets',
    attributes: {
        product_id: { type: 'string', default: 'Product ID' },
        columns: { type: 'string', default: '3' },
        size: { type: 'string', default: 'thumbnail' }
    },
    edit: function(props) {
        return wp.element.createElement('div', useBlockProps(),
            wp.element.createElement(TextControl, {
                label: 'Product ID',
                value: props.attributes.product_id,
                onChange: function(product_id) { props.setAttributes({ product_id: product_id }) },
                placeholder: 'Enter Product ID'
            }),
            wp.element.createElement(TextControl, {
                label: 'Columns',
                value: props.attributes.columns,
                onChange: function(columns) { props.setAttributes({ columns: columns }) },
                placeholder: 'Enter number of columns (e.g., 3)'
            }),
            wp.element.createElement(TextControl, {
                label: 'Image Size',
                value: props.attributes.size,
                onChange: function(size) { props.setAttributes({ size: size }) },
                placeholder: 'Enter image size (e.g., thumbnail, medium, large)'
            }),
            wp.element.createElement('p', {}, `Shortcode Output: [WPS_POST_GALLERY product_id="${props.attributes.product_id}" columns="${props.attributes.columns}" size="${props.attributes.size}"]`)
        );
    },
    save: function(props) {
        return wp.element.createElement('div', useBlockProps.save(), `[WPS_POST_GALLERY product_id="${props.attributes.product_id}" columns="${props.attributes.columns}" size="${props.attributes.size}"]`);
    }
});
