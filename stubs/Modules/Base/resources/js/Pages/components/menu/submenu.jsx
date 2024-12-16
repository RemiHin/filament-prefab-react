import SmartLink from "./smartlink.jsx";

export default function Submenu(props) {
    const {parent, items} = props;
    return (
        <ul className={`ml-4 list-none ${props.className}`}>
            {parent !== undefined && (
                <li>
                    <SmartLink
                        href={parent.route}
                        className="flex py-1.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current">
                        {parent.title}
                    </SmartLink>
                </li>
            )}

            {items.map((item, index) => (
                <li key={index}>
                    <SmartLink
                        href={item.route}
                        isExternal={!item.is_internal}
                        className="flex items-center py-1.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current"
                    >
                        {item.title}
                    </SmartLink>
                </li>
            ))}
        </ul>
    )
}
