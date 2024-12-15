import {useEffect, useRef, useState} from 'react';
import BlockModule from "./block-module.jsx";
import CloseSvg from "../svg/close.jsx";

export default function ToggleContentBlock({data}) {
    const [open, setOpen] = useState(false);
    const contentRef = useRef(null);
    const [height, setHeight] = useState(0);

    useEffect(() => {
        if (contentRef.current) {
            setHeight(open ? contentRef.current.scrollHeight : 0);
        }
    }, [open, data.content]);

    return (
        <div className="w-full max-w-[768px] px-5 mx-auto mb-10 lg:mb-16">
            <div
                className="flex flex-col w-full p-5 border border-gray-200 hover:border-gray-300 rounded-md lg:rounded-lg transition-colors duration-150 ease-in-out">
                <h2 className="flex flex-col w-full">
                    <button
                        onClick={() => setOpen(!open)}
                        aria-expanded={open}
                        className="font-family font-bold text-lg lg:text-xl flex flex-row justify-between text-left"
                    >
                        {data.title}

                        <div
                            className={`transition-transform duration-300 ease-in-out ${open ? 'rotate-0' : 'rotate-45'}`}>
                            <CloseSvg className="h-5 w-5"/>
                        </div>
                    </button>
                </h2>

                <div
                    ref={contentRef}
                    style={{height: `${height}px`}}
                    className="transition-[height] duration-300 ease-in-out overflow-hidden"
                >
                    <div className={`transition-opacity duration-300 ${open ? 'opacity-100' : 'opacity-0'}`}>
                        <BlockModule blocks={data.content}/>
                    </div>
                </div>
            </div>
        </div>
    );
}
