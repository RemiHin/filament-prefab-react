import Header from "./header.jsx";
import Footer from "./footer.jsx";

export default function Layout({children}) {
    return (
        <>
            <Header/>
            <div className="flex-grow w-full">
                <main className={'max-w-7xl mx-auto'}>
                    {children}
                </main>
            </div>
            <Footer/>
        </>
    )
}
