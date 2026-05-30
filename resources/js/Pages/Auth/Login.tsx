import { useForm, Head } from '@inertiajs/react';
import React, { useState } from 'react';
import { Eye, EyeOff } from 'lucide-react';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    });

    const [showPassword, setShowPassword] = useState(false);

    const togglePassword = () => setShowPassword(!showPassword);

    return (
        <div className="min-h-screen bg-gray-100 flex items-center justify-center p-4">
            <Head title="Log in" />
        <div
            className="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row border border-gray-200">

            <div className="w-full md:w-1/2 p-8 md:p-12">
                <div className="flex items-center justify-center gap-1 mb-6">
                    <img src="/images/logo.png" alt="Logo Print App" className="w-20 h-20 object-contain" />

                    <span className="text-5xl font-koulen">PRINTATION</span>
                </div>

                <h1 className="text-3xl font-koulen text-center text-gray-800 mb-8 uppercase tracking-wide">SELAMAT DATANG!
                </h1>

                <form onSubmit={(e) => { e.preventDefault(); post('/login'); }} className="space-y-5">
                    <div>
                        <label className="block text-sm font-bold text-gray-700 mb-2 ml-1">Email</label>
                        <input 
                            value={data.email} 
                            onChange={e => setData('email', e.target.value)}
                            type="email" 
                            name="email" 
                            placeholder="Masukkan email"
                            className="w-full bg-gray-50 text-gray-800 rounded-lg p-3 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                            required 
                        />
                        {errors.email && <div className="text-red-500 text-sm mt-1">{errors.email}</div>}
                    </div>

                    <div>
                        <label className="block text-sm font-bold text-gray-700 mb-2 ml-1">Password</label>
                        <div className="relative">
                            <input 
                                value={data.password} 
                                onChange={e => setData('password', e.target.value)}
                                type={showPassword ? 'text' : 'password'} 
                                name="password"
                                id="password" 
                                placeholder="Masukkan password"
                                className="w-full bg-gray-50 text-gray-800 rounded-lg p-3 pr-12 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                                required 
                            />

                            <button type="button" onClick={togglePassword}
                                className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                                {showPassword ? (
                                    <EyeOff className="w-5 h-5" />
                                ) : (
                                    <Eye className="w-5 h-5" />
                                )}
                            </button>
                        </div>
                        {errors.password && <div className="text-red-500 text-sm mt-1">{errors.password}</div>}
                    </div>

                    <button type="submit"
                        className="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-500/30 mt-4 cursor-pointer disabled:opacity-50"
                        disabled={processing}>
                        Masuk
                    </button>
                </form>
            </div>

            <div className="hidden md:flex w-full md:w-1/2 bg-[#015DB4] items-center justify-center p-10 relative">
                <div className="absolute bottom-0 left-0 w-full h-1/2 bg-linear-to-t from-blue-700/50 to-transparent">
                </div>

                <img src="/images/login-image.png" alt="Login Illustration"
                    className="relative z-10 w-full max-w-sm h-auto object-contain drop-shadow-2xl" />
            </div>

        </div>
    </div>
    );
}

