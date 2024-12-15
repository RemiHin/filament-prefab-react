export default function ImageBlock({data}) {
    return (
        <section className="mb-5 lg:mb-10">
            <div className="container max-w-container-medium">
                <img src={data.large}/>
            </div>
        </section>
    )
}
