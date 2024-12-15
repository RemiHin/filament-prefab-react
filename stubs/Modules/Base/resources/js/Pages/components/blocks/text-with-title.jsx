export default function TextWithTitleBlock({text, title}) {
    return (
        <section className="mb-5 lg:mb-10">
            <div className="container max-w-container-small prose prose-lg ">
                <h2>
                    {title}
                </h2>

                <div
                    dangerouslySetInnerHTML={{
                        __html: text
                    }}
                    className="prose prose-lg container max-w-container-small">
                </div>
            </div>
        </section>
    )
}
