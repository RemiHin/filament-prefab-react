import React from "react";
import {router} from "@inertiajs/react";

export default function Pagination({links, currentPage, setCurrentPage}) {
    const handlePageChange = (url) => {
        const pageParam = new URL(url).searchParams.get('page');
        setCurrentPage(pageParam);
        router.get(url, {preserveState: true})
    }

    return (
        <nav>
            <ul className={'flex flex-row items-center justify-center gap-5'}>
                {links.map((link, index) => (
                    <li key={link.label}>
                        <a
                            href={link.url}
                            onClick={(e) => {
                                e.preventDefault();
                                handlePageChange(link.url)
                            }}
                            dangerouslySetInnerHTML={{__html: link.label}}
                            className={`flex flex-col ${link.active ? 'font-bold' : ''}`}
                        />
                    </li>
                ))}
            </ul>
        </nav>
    )
}
