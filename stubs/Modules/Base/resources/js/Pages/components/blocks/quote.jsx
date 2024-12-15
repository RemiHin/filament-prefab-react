export default function QuoteBlock({text}) {
    return (
        <section className="mb-5 lg:mb-10">
            <div className="container max-w-container-medium">
                <div
                    dangerouslySetInnerHTML={{
                        __html: `"${text}"`
                    }}
                    className="p-5 lg:p-10 text-xl bg-white">
                </div>
            </div>
        </section>
    )
};
