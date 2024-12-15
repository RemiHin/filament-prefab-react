import React, {useEffect, useState} from 'react';

const DynamicIcon = ({name, className = ''}) => {
    const [svgContent, setSvgContent] = useState('');
    const [error, setError] = useState(null);

    // Get the actual icon name without the 'icon-' prefix
    const getIconName = (fullName) => {
        return fullName.startsWith('icon-') ? fullName.substring(5) : fullName;
    };

    useEffect(() => {
        const fetchSvg = async () => {
            try {
                const iconName = getIconName(name);
                const response = await fetch(`/images/svg/${iconName}.svg`);
                if (!response.ok) {
                    throw new Error(`Failed to load SVG: ${iconName}`);
                }
                const text = await response.text();

                // Create a temporary DOM element to parse the SVG
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'image/svg+xml');
                const svg = doc.querySelector('svg');

                // Copy existing classes and add new ones
                if (className) {
                    const existingClasses = svg.getAttribute('class') || '';
                    svg.setAttribute('class', `${existingClasses} ${className}`.trim());
                }

                // Get the cleaned SVG markup
                setSvgContent(svg.outerHTML);
            } catch (err) {
                console.error('Error loading SVG:', err);
                setError(err.message);
            }
        };

        if (name) {
            fetchSvg();
        }
    }, [name, className]);

    if (error) {
        console.error(`Error loading icon: ${name}`);
        return null;
    }

    return svgContent ? (
        <div
            dangerouslySetInnerHTML={{__html: svgContent}}
            className="inline-block"
        />
    ) : (
        <div className="animate-pulse w-6 h-6 bg-gray-200 rounded"/>
    );
};

export default DynamicIcon;
