import SmartLink from "./smartlink.jsx";
import {usePage} from "@inertiajs/react";

export default function Submenu(props) {
    const {parent, items} = props;
    const {url} = usePage()

    return (
        <ul className={`ml-4 list-none ${props.className}`}>
            {parent !== undefined && (
                <li>
                    <SmartLink
                        href={parent.route}
                        className={`${url === (parent.menuable?.slug ? '/' + parent.menuable?.slug : parent.route) ? 'font-bold' : ''} flex py-1.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current`}>
                        {parent.title}
                    </SmartLink>
                </li>
            )}

            {items.map((item, index) => (
                <li key={index}>
                    <SmartLink
                        href={item.menuable?.slug ?? item.route}
                        isExternal={!item.is_internal}
                        className={`${url === (item.menuable?.slug ? '/' + item.menuable?.slug : item.route) ? 'font-bold' : ''} flex items-center py-1.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current`}
                    >
                        {item.title}
                    </SmartLink>
                </li>
            ))}
        </ul>
    )
}
