import {Link} from '@inertiajs/react'
import ExternalLinkSvg from "../svg/external-link.jsx";

const SmartLink = ({href, children, className = '', isExternal}) => {

    if (isExternal) {
        return (
            <a
                href={href}
                target="_blank"
                rel="noopener noreferrer"
                className={className}
            >
                {children}
                <ExternalLinkSvg className={'h-4 w-4 ml-1'}/>
            </a>
        )
    }

    // Als het geen externe URL is, gebruik dan de Inertia Link
    return (
        <Link href={href} className={className}>
            {children}
        </Link>
    )
}
export default SmartLink;
