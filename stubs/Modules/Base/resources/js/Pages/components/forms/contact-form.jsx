import React from 'react';
import {useForm} from '@inertiajs/react';

export default function ContactForm() {
    const {data, setData, post, processing, errors, reset, recentlySuccessful} = useForm({
        name: '',
        email: '',
        message: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/contact', {
            onSuccess: () => {
                reset('name', 'email', 'message');
            },
        });
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            {errors.form && (
                <div className="p-4 text-red-700 bg-red-100 rounded-lg">
                    {errors.form}
                </div>
            )}

            {recentlySuccessful && (
                <div className="p-4 text-green-700 bg-green-100 rounded-lg">
                    Message sent successfully!
                </div>
            )}

            <div>
                <label
                    htmlFor="name"
                    className="block text-sm font-medium text-gray-700"
                >
                    Name
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    value={data.name}
                    onChange={e => setData('name', e.target.value)}
                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                {errors.name && (
                    <div className="mt-1 text-sm text-red-600">
                        {errors.name}
                    </div>
                )}
            </div>

            <div>
                <label
                    htmlFor="email"
                    className="block text-sm font-medium text-gray-700"
                >
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value={data.email}
                    onChange={e => setData('email', e.target.value)}
                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                {errors.email && (
                    <div className="mt-1 text-sm text-red-600">
                        {errors.email}
                    </div>
                )}
            </div>

            <div>
                <label
                    htmlFor="message"
                    className="block text-sm font-medium text-gray-700"
                >
                    Message
                </label>
                <textarea
                    id="message"
                    name="message"
                    required
                    rows={4}
                    value={data.message}
                    onChange={e => setData('message', e.target.value)}
                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                {errors.message && (
                    <div className="mt-1 text-sm text-red-600">
                        {errors.message}
                    </div>
                )}
            </div>

            <button
                type="submit"
                disabled={processing}
                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {processing ? 'Sending...' : 'Send Message'}
            </button>
        </form>
    );
}
