import TextBlock from "./text.jsx";
import ImageBlock from "./image.jsx";

export default function TextWithImageBlock({position, text, data}) {
    return (
        <section className="mb-5 lg:mb-10">
            <div className="container max-w-container">
                <div
                    className={`flex gap-5 w-full ${position === 'right' ? 'flex-col md:flex-row-reverse' : 'flex-col md:flex-row'}`}>
                    <div className="w-full md:w-1/2">
                        <ImageBlock data={data}/>
                    </div>
                    <TextBlock text={text}/>
                </div>
            </div>
        </section>
    )
}
