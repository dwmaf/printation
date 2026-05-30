import React from 'react';
import { Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginationProps {
    links: PaginationLink[];
}

export default function Pagination({ links }: PaginationProps) {
    if (!links || links.length === 0) return null;

    if (links.length > 3) {
        return (
            <div className="inline-flex items-stretch border border-gray-300 dark:border-gray-600 divide-x-2 divide-gray-300 dark:divide-gray-600 rounded-lg overflow-hidden">
                {links.map((link, key) => {
                    if (key === 0 && link.url) {
                        return (
                            <Link key={key} href={link.url ?? '#'} disabled={!link.url}
                                className={`px-2 py-2 text-sm font-bold flex items-center dark:text-gray-100 text-gray-700 justify-center ${
                                    !link.url ? 'cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-700'
                                }`}>
                                <ChevronLeft className="w-4 h-4 " />
                            </Link>
                        );
                    } else if (key === links.length - 1 && link.url) {
                        return (
                            <Link key={key} href={link.url ?? '#'} disabled={!link.url}
                                className={`px-2 py-2 text-sm font-bold flex items-center dark:text-gray-100 text-gray-700 justify-center ${
                                    !link.url ? 'cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-700'
                                }`}>
                                <ChevronRight className="w-4 h-4 " />
                            </Link>
                        );
                    } else if (key !== 0 && key !== links.length - 1) {
                        if (link.url === null) {
                            return (
                                <div key={key} className="px-3 py-2 text-sm text-gray-500" dangerouslySetInnerHTML={{ __html: link.label }} />
                            );
                        } else {
                            return (
                                <Link key={key} 
                                    className={`px-3 py-2 text-sm font-bold hover:bg-gray-100 dark:hover:bg-gray-700 ${
                                        link.active ? 'text-indigo-700 dark:text-indigo-400' : 'dark:text-white text-gray-700'
                                    }`}
                                    href={link.url} 
                                    dangerouslySetInnerHTML={{ __html: link.label }} 
                                />
                            );
                        }
                    }
                    return null;
                })}
            </div>
        );
    }
    
    // Fallback for length < 3
    return (
        <div className="flex justify-between w-full">
            {links[0]?.url ? (
                <Link href={links[0].url}
                    className="px-4 py-2 text-sm font-bold flex items-center dark:text-gray-100 text-gray-700 justify-center border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span>Previous</span>
                </Link>
            ) : <div></div>}
            
            {links[links.length - 1]?.url && (
                <Link href={links[links.length - 1].url!}
                    className="px-4 py-2 text-sm font-bold flex items-center dark:text-gray-100 text-gray-700 justify-center border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span>Next</span>
                </Link>
            )}
        </div>
    );
}