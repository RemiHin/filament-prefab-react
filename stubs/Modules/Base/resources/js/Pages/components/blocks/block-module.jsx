import TextBlock from "./text.jsx";
import QuoteBlock from "./quote.jsx";
import TextWithTitleBlock from "./text-with-title.jsx";
import ToggleContentBlock from "./toggle-content.jsx";
import ImageBlock from "./image.jsx";
import TextWithImageBlock from "./text-with-image.jsx";

export default function BlockModule({blocks}) {
    return blocks.map((block, index) => {
        if (block.type === 'text') {
            return <TextBlock key={`block_${index}`} text={block.data.text}/>
        }

        if (block.type === 'text-with-title') {
            return <TextWithTitleBlock key={`block_${index}`} text={block.data.text} title={block.data.title}/>
        }

        if (block.type === 'quote') {
            return <QuoteBlock key={`block_${index}`} text={block.data.quote}/>
        }

        if (block.type === 'toggle-content') {
            return <ToggleContentBlock key={`block_${index}`} data={block.data}/>
        }

        if (block.type === 'image') {
            return <ImageBlock key={`block_${index}`} data={block.data}/>
        }

        if (block.type === 'text-with-image') {
            return <TextWithImageBlock
                key={`block_${index}`}
                text={block.data.text}
                position={block.data.position}
                data={block.data}
            />
        }

    })
}
