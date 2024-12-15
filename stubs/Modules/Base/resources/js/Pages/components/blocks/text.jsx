export default function TextBlock({text}) {
    return (
        <section className="mb-5 lg:mb-10">
            <div
                dangerouslySetInnerHTML={{
                    __html: text
                }}
                className="prose prose-lg container max-w-container-small">
            </div>
        </section>
    )
}
