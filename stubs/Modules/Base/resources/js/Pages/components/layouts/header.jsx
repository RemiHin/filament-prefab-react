import MainMenu from "../menu/main-menu.jsx";
import {useState} from "react";
import HamburgerSvg from "../svg/hamburger.jsx";
import TopMenu from "../menu/top-menu.jsx";
import SlideOver from "../menu/slideover.jsx";

export default function Header() {
    const [open, setOpen] = useState(false);

    return (
        <>
            <header className={'sticky w-full flex flex-col bg-white top-0 shadow-lg z-20'}>
                <div className="flex flex-row items-center justify-end text-sm py-2 px-5">
                    <nav className="hidden lg:flex gap-4">
                        <TopMenu className="flex flex-row text-sm items-center gap-2 pl-0"/>
                    </nav>
                </div>

                <div className="flex flex-row items-center justify-between py-2 px-5">
                    <a href="/">
                        <img src={'assets/logo.svg'} className="h-12" alt=""/>
                    </a>

                    <nav className="hidden lg:flex gap-4">
                        <MainMenu className="flex flex-row items-center gap-2 pl-0"/>
                    </nav>

                    <div className="mobile-menu flex flex-row flex-nowrap gap-4 items-center lg:hidden">
                        <button
                            onClick={() => setOpen(!open)}
                            type={'button'}
                            className="js-toggle-mobile-menu js-mobile-menu-button btn btn-primary btn-small"
                            title="{{ __('Menu') }}"
                            aria-label="{{ __('Open menu') }}"
                            aria-expanded="false"
                        >
                            <HamburgerSvg className="h-6 w-6"/>
                        </button>
                    </div>
                </div>

                <SlideOver
                    open={open}
                    onClose={() => setOpen(false)}
                    className={'flex flex-col flex-grow justify-between gap-5'}
                >
                    <nav className="flex flex-col">
                        <MainMenu className="flex flex-col justify-start items-start"/>
                    </nav>
                    <nav className="flex text-sm">
                        <TopMenu className="flex flex-col pl-0 border-0"/>
                    </nav>
                </SlideOver>
            </header>
        </>
    )
}
