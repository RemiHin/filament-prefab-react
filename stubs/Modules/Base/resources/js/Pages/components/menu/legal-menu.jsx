import {usePage} from "@inertiajs/react";
import MenuItem from "./menu-item.jsx";

export default function LegalMenu(props) {
    const {menus} = usePage().props;
    const menuItems = menus.legal;
    return (
        <ul className={`list-none ${props.className}`}>
            {menuItems.map(item => {
                return (
                    <MenuItem key={`legal_${item.id}`} collapsable={false} item={item} title={'legal'}/>
                )
            })}
        </ul>
    )
}
