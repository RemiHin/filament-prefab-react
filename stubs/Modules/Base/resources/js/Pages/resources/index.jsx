import Layout from "../components/layouts/layout.jsx";
import {Head} from "@inertiajs/react";

export default function Index({model}) {
    return (
        <Layout>
            <Head title={model.name}/>
            <h1>
                This is the default view
            </h1>
        </Layout>
    )
}
