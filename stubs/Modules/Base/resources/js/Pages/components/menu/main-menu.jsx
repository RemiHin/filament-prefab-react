import {usePage} from "@inertiajs/react";
import MenuItem from "./menu-item.jsx";

export default function MainMenu(props) {
    const {menus} = usePage().props;
    const menuItems = menus.main

    return (
        <ul
            className={`list-none lg:flex lg:justify-start lg:items-center text-sm font-semibold text-brand-dark ${props.className}`}>
            {menuItems.map(item => {
                return (
                    <MenuItem key={`main_${item.id}`} collapsable={true} item={item} title={'main'}/>
                )
            })}
        </ul>
    )
}


