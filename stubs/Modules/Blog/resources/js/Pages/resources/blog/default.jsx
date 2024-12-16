import Layout from "../../components/layouts/layout.jsx";
import BlockModule from "../../components/blocks/block-module.jsx";

export default function Default({blog}) {
    return (
        <Layout>
            <div className={'flex justify-between items-center'}>
                <div className={'w-full text-center'}>
                    <h1 className={'font-bold text-5xl'}>{blog.name}</h1>
                    <p>
                        {blog.intro}
                    </p>
                </div>
                <img className={'rounded-xl max-w-2xl'} src={blog.image.large_url}/>
            </div>

            <BlockModule blocks={blog.content}/>
        </Layout>
    );
}
