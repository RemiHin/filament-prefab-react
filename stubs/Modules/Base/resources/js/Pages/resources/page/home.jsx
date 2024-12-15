import Layout from "../../components/layouts/layout.jsx";
import BlockModule from "../../components/blocks/block-module.jsx";

export default function Home({page}) {
    return (
        <Layout>
            <h1>Hello Users!</h1>
            {page.content?.length > 0 && (
                <BlockModule blocks={page.content}/>
            )}
        </Layout>
    );
}
