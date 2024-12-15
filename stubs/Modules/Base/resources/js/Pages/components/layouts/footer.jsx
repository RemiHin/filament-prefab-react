import PhoneSvg from "../svg/phone.jsx";
import MailSvg from "../svg/mail.jsx";
import {usePage} from "@inertiajs/react";
import Socials from "./socials.jsx";
import FooterMenu from "../menu/footer-menu.jsx";
import LegalMenu from "../menu/legal-menu.jsx";

export default function Footer() {
    const {appName, contact} = usePage().props

    return (
        <>
            <footer className={'max-w-7xl mx-auto relative flex-col flex-shrink-0 bg-white border-t-2 border-slate-100'}>
                <div className="container max-w-container flex pt-10 pb-5 md:pb-10">
                    <div className="w-full flex flex-col md:flex-row items-start gap-5 md:gap-8 lg:gap-12">
                        <div className="flex flex-col gap-5 flex-shrink-0 self-center md:self-start">
                            <img
                                src={'assets/logo.svg'}
                                className="w-full max-w-[180px]"
                                alt={''}
                            />

                            <ul className="list-none pl-0 flex flex-col gap-2.5 self-start">
                                {contact.phone !== undefined && (
                                    <li className="flex">
                                        <a
                                            href={`tel:${contact.phone}`}
                                            target="_blank"
                                            className="flex flex-row-reverse items-center self-end gap-2.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current"
                                        >
                                            {contact.phone}

                                            <PhoneSvg className="h-5 w-5"/>
                                        </a>
                                    </li>
                                )}

                                {contact.email !== undefined && (
                                    <li className="flex">
                                        <a
                                            href={`mailto:${contact.email}`}
                                            target="_blank"
                                            className="flex flex-row-reverse items-center self-end gap-2.5 underline underline-offset-4 decoration-2 decoration-transparent transition duration-150 ease-in-out hover:decoration-current"
                                        >
                                            {contact.email}

                                            <MailSvg className="h-5 w-5"/>
                                        </a>
                                    </li>
                                )}
                            </ul>
                            <Socials/>
                        </div>

                        <div className="flex w-full">
                            <FooterMenu/>
                        </div>
                    </div>
                </div>

                <div className="w-full bg-default-accent">
                    <div
                        className="container max-w-container flex flex-col-reverse md:flex-row md:items-center md:justify-center gap-4 py-5">
                        <span className="font-semibold text-sm">
                            Copyright {new Date().getFullYear()}. All rights reserved {appName}
                        </span>
                        <LegalMenu className="flex flex-col md:flex-row md:items-center flex-wrap gap-4 pl-0 text-sm"/>
                    </div>
                </div>
            </footer>
        </>
    )
}
