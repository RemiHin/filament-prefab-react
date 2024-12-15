import {usePage} from "@inertiajs/react";
import FacebookSvg from "../svg/facebook.jsx";
import InstagramSvg from "../svg/instagram.jsx";
import LinkedInSvg from "../svg/linkedin.jsx";
import TwitterSvg from "../svg/twitter.jsx";
import YoutubeSvg from "../svg/youtube.jsx";

export default function Socials() {
    const { socials } = usePage().props

    return (
        <>
            <ul className="list-none flex flex-row gap-4 pl-0">
                {socials.facebook !== undefined && (
                    <li>
                        <a
                            href={socials.facebook}
                            target="_blank"
                            rel="noopener"
                            className="flex"
                        >
                            <FacebookSvg className="w-8 h-8"/>
                        </a>
                    </li>
                )}

                {socials.instagram !== undefined && (
                    <li>
                        <a
                            href={socials.instagram}
                            target="_blank"
                            rel="noopener"
                            className="flex"
                        >
                            <InstagramSvg className="w-8 h-8"/>
                        </a>
                    </li>
                )}

                {socials.linkedin !== undefined && (
                    <li>
                        <a
                            href={socials.linkedin}
                            target="_blank"
                            rel="noopener"
                            className="flex"
                        >
                            <LinkedInSvg className="w-8 h-8"/>
                        </a>
                    </li>
                )}

                {socials.twitter !== undefined && (
                    <li>
                        <a
                            href={socials.twitter}
                            target="_blank"
                            rel="noopener"
                            className="flex"
                        >
                            <TwitterSvg className="w-8 h-8"/>
                        </a>
                    </li>
                )}

                {socials.youtube !== undefined && (
                    <li>
                        <a
                            href={socials.youtube}
                            target="_blank"
                            rel="noopener"
                            className="flex"
                        >
                            <YoutubeSvg className="w-8 h-8"/>
                        </a>
                    </li>
                )}
            </ul>
        </>
    )
}
