import Layout from "../../components/layouts/layout.jsx";
import {Head} from "@inertiajs/react";

export default function Cookies({page}) {
    return (
        <Layout>
            <Head title={page.name}/>
            <h1>Hello Cookies!</h1>
        </Layout>
    );
}
