import {usePage} from "@inertiajs/react";
import MenuItem from "./menu-item.jsx";

export default function FooterMenu() {
    const {menus} = usePage().props;
    const menuItems = menus.footer
    return (
        <ul className={'list-none'}>
            {menuItems.map(item => {
                    return (
                        <MenuItem key={`footer_${item.id}`}
                                  collapsable={false}
                                  item={item}
                                  title={'footer'}/>
                    )
                }
            )}
        </ul>
    )
}
