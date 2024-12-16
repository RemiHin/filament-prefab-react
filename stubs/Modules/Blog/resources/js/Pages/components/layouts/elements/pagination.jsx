export default function Pagination({data}) {
    return (
        <>
            <div className={'flex flex-row items-center justify-center gap-5'}>
                {data.links.map((link, index) => (
                    <a
                        href={link.url}
                        key={'link_' + index}
                        type={'button'}
                        dangerouslySetInnerHTML={{__html: link.label}}
                        className={`flex flex-col ${link.active ? 'font-bold' : ''}`}
                    />
                ))}
            </div>
        </>
    )
}
