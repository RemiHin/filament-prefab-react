import React from 'react';
import CloseSvg from "../svg/close.jsx";

export default function SlideOver({open, onClose, children, title = 'Menu', className}) {
    // Handle escape key
    React.useEffect(() => {
        const handleEscape = (e) => {
            if (e.key === 'Escape') onClose();
        };

        if (open) {
            document.addEventListener('keydown', handleEscape);
        }

        return () => document.removeEventListener('keydown', handleEscape);
    }, [open, onClose]);

    return (
        <>
            {/* Backdrop */}
            <div
                className={`fixed inset-0 bg-slate-700/25 transition-opacity duration-300 ease-in-out
          ${open ? 'opacity-100' : 'opacity-0 pointer-events-none'}`}
                onClick={onClose}
                aria-hidden="true"
            />

            {/* SlideOver Panel */}
            <section
                className={`fixed top-0 right-0 h-full w-full max-w-[380px] transform transition-transform duration-300 ease-in-out
          ${open ? 'translate-x-0' : 'translate-x-full'}`}
                role="dialog"
                aria-modal="true"
                aria-labelledby="slide-over-title"
            >
                <div className="flex flex-col h-full bg-white">
                    {/* Header */}
                    <div className="flex justify-between items-center p-4 border-b border-slate-200">
                        <h2 id="slide-over-title" className="heading-3">
                            {title}
                        </h2>
                        <button
                            type="button"
                            onClick={onClose}
                            className="p-1 rounded-md hover:bg-slate-100 transition-colors"
                            aria-label="Close menu"
                        >
                            <CloseSvg className="w-6 h-6 text-gray-400"/>
                        </button>
                    </div>

                    {/* Content */}
                    <div className={`flex-1 overflow-y-auto px-4 py-5 ${className}`}>
                        {children}
                    </div>
                </div>
            </section>
        </>
    );
};
