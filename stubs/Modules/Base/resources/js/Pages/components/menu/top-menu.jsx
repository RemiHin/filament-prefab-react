import {usePage} from "@inertiajs/react";
import MenuItem from "./menu-item.jsx";

export default function TopMenu(props) {
    const {menus} = usePage().props;
    const menuItems = menus.top;
    return (
        <ul
            className={`list-none lg:flex lg:justify-start lg:items-center text-sm font-semibold text-brand-dark ${props.className}`}>
            {menuItems.map(item => {
                return (
                    <MenuItem key={`top_${item.id}`} collapsable={true} item={item} title={'top'}/>
                )
            })}
        </ul>
    )
}
