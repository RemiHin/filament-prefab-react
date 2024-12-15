import ExternalLinkSvg from "../svg/external-link.jsx";
import ChevronDownSvg from "../svg/chevron-down.jsx";
import Submenu from "./submenu.jsx";
import {useState} from "react";

export default function MenuItem(props) {
    const {item, collapsable, title} = props;
    const [isHovered, setIsHovered] = useState(false);

    if (!collapsable) {
        if (item.children.length > 0) {
            return (
                <li key={`${title}_${item.id}`}>
                    <span className="flex w-full py-2 border-t border-slate-200 md:border-none font-bold">
                        {item.title}
                    </span>

                    <Submenu
                        parent={item}
                        items={item.children}
                        className={'relative w-full flex flex-col mb-2 md:mb-0 gap-0.5 lg:gap-1 px-0'}
                    />
                </li>
            )
        }

        return (
            <li className="relative" key={`${title}_${item.id}`}>
                <a
                    target={item.is_internal ? `_self` : `_blank`}
                    className="flex items-center w-full py-2 border-t border-slate-200 md:border-none underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current"
                    href={item.route}
                >
                    {item.title}
                    {!item.is_internal && (
                        <ExternalLinkSvg className={'h-4 w-4 ml-1'}/>
                    )}
                </a>
            </li>
        )
    }

    if (!item.is_internal) {
        return (
            <a href={item.route}
               key={`${title}_${item.id}`}
               target={`_blank`}
               className={`${item.children.length > 0 ? 'hidden' : 'flex'} items-center px-5 py-2.5 bg-transparent text-gray-900 rounded-md mb-2 lg:mb-0`}>
                {item.title}
                <ExternalLinkSvg className={'h-4 w-4 ml-1'}/>
            </a>
        )
    }

    if (item.children.length <= 0 && item.is_internal) {
        return (
            <a href={item.route}
               key={`${title}_${item.id}`}
               className={`${item.children.length > 0 ? 'hidden' : 'flex'} items-center px-5 py-2.5 bg-transparent text-gray-900 rounded-lg mb-2 lg:mb-0`}>
                {item.title}
            </a>
        )
    }

    if (item.children.length > 0) {
        return (
            <li
                className="relative border-b border-slate-200 lg:border-none"
                onMouseEnter={() => setIsHovered(true)}
                onMouseLeave={() => setIsHovered(false)}
                key={`${title}_${item.id}`}
            >
                <span
                    className="flex lg:inline-flex items-center py-2 px-4 transition duration-150 ease-in-out text-brand-dark"
                    tabIndex="0"
                    aria-expanded={isHovered}
                    role="button">
                    {item.title}
                    <ChevronDownSvg
                        className={`h-5 w-5 ml-2 transition duration-300 ${isHovered ? 'rotate-180' : 'rotate-0'}`}/>
                </span>

                <div className="overflow-hidden">
                    <Submenu
                        parent={item}
                        items={item.children}
                        className={`transition-all duration-300 ease-in-out overflow-hidden
                       ${isHovered ? 'opacity-100 gap-1.5 pl-4 mb-2 lg:p-4 lg:mb-0' : 'gap-1.5 lg:gap-0 mb-2 lg:mb-0 lg:h-0 lg:opacity-100'}
                       flex flex-col px-4
                       lg:gap-3 lg:absolute lg:min-w-full lg:right-0 lg:bottom-0
                       lg:transform lg:translate-y-full lg:bg-white lg:rounded
                       lg:shadow-md `}
                    />
                </div>
            </li>
        )
    }
}
