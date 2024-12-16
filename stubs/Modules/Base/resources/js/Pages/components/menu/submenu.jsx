import ExternalLinkSvg from "../svg/external-link.jsx";
import {Link} from "@inertiajs/react";

export default function Submenu(props) {
    const {parent, items} = props;
    return (
        <ul className={`ml-4 list-none ${props.className}`}>
            {parent !== undefined && (
                <li>
                    <Link href={parent.route}
                          className="flex py-1.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current">
                        {parent.title}
                    </Link>
                </li>
            )}

            {items.map((item, index) => (
                <li key={index}>
                    <Link href={item.route}

                          target={item.is_internal ? `_self` : `_blank`}
                          className="flex items-center py-1.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current"
                    >
                        {item.title}
                        {!item.is_internal && (
                            <ExternalLinkSvg className={'h-4 w-4 ml-1'}/>
                        )}
                    </Link>
                </li>
            ))}
        </ul>
    )
}
