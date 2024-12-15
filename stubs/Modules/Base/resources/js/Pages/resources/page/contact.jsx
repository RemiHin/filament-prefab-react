import Layout from "../../components/layouts/layout.jsx";
import ContactForm from "../../components/forms/contact-form.jsx";

export default function Contact() {
    return (
        <Layout>
            <h1>Hello Contact!</h1>
            <div className="w-full md:w-1/2">
                <ContactForm/>
            </div>
        </Layout>
    );
}
