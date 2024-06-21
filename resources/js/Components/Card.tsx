import clsx from 'clsx';
import React, {ReactNode} from 'react';
import {OtherProps} from '@/types';
import {BlurhashCanvas} from 'react-blurhash';

const Card = ({children, className = '', ...props}: {children: ReactNode; className?: string; props?: OtherProps;}) => {
    return (
        <div
            className={clsx('shadow dark:shadow-black/25 bg-white dark:bg-gray-800 rounded-lg contain-paint', className)}
            {...props}
        >
            {children}
        </div>
    );
};

const Image = ({src, alt, blurHash = null}: {src: string; alt: string; blurHash: string | null;}) => {
    return (
        <div className="relative flex items-center h-72 md:h-56 overflow-hidden rounded-t-lg">
            <img
                loading="lazy"
                src={src}
                alt={alt}
                className="z-10 object-contain w-full"
            />

            {blurHash
                ? <BlurhashCanvas hash={blurHash} className="absolute w-full h-full"/>
                : <div className="absolute size-full blur-xl" style={{backgroundImage: `url(${src})`}}/>
            }
        </div>
    );
};

const Header = ({title, description}: { title: string; description?: string; }) => {
    return (
        <div className="px-4 pt-4">
            <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                {title}
            </h2>

            {description && (
                <div className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {description}
                </div>
            )}
        </div>
    );
};

const HeaderLink = ({href, children}: { href: string; children: ReactNode; }) => {
    return (
        <a
            href={href}
            className="link-primary inline-block text-xl font-semibold leading-6"
        >
            {children}
        </a>
    );
};

const Body = ({children, className = '', ...props}: { children: ReactNode; className?: string; props?: OtherProps; }) => {
    return (
        <div
            className={clsx('p-4', className)}
            {...props}
        >
            {children}
        </div>
    );
};

Card.Image = Image;
Card.Header = Header;
Card.HeaderLink = HeaderLink;
Card.Body = Body;

export default Card;
