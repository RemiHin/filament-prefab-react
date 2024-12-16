import Layout from "../../components/layouts/layout.jsx";
import BlockModule from "../../components/blocks/block-module.jsx";
import Pagination from "../../components/layouts/elements/pagination.jsx";
import {useForm} from "@inertiajs/react";

export default function BlogOverview({page, blogs}) {
    const {data, setData} = useForm({
        page: blogs.current_page
    })

    return (
        <Layout>
            <h1>{page.name}</h1>
            {page.content &&
                <BlockModule blocks={page.content}/>
            }
            <div className={'grid grid-cols-3 gap-5'}>
                {blogs.data.map((blog, index) => (
                    <a href={blog.url} key={index}>
                        {/*TODO: add srcSet to image using large_url, medium_url and thumbnail_url*/}
                        <img className={'rounded-xl'} src={blog.image.medium_url}/>
                        <h3>
                            {blog.name}
                        </h3>
                        <p className={'truncate'}>
                            {blog.intro}
                        </p>
                    </a>
                ))}

            </div>
            <Pagination links={blogs.links} currentPage={blogs.currentPage} setCurrentPage={(page) => setData(page)}/>
        </Layout>
    );
}
