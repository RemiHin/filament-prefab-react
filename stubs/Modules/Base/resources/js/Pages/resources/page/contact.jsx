import Layout from "../../components/layouts/layout.jsx";
import ContactForm from "../../components/forms/contact-form.jsx";
import {Head} from "@inertiajs/react";

export default function Contact({page}) {
    return (
        <Layout>
            <Head title={page.name}/>
            <h1>Hello Contact!</h1>
            <div className="w-full md:w-1/2">
                <ContactForm/>
            </div>
        </Layout>
    );
}
