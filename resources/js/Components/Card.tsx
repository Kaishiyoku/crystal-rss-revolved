import clsx from 'clsx';
import PhotoSolidIcon from '@/Icons/PhotoSolidIcon';
import {ReactNode} from 'react';
import {OtherProps} from '@/types';

const Card = ({children, className = '', ...props}: {children: ReactNode; className?: string; props?: OtherProps;}) => {
    return (
        <div
            className={clsx('shadow-md dark:shadow-black/25 bg-white dark:bg-gray-800 rounded-lg', className)}
            {...props}
        >
            {children}
        </div>
    );
};

const Image = ({src, alt}: {src: string; alt: string;}) => {
    return (
        <img
            loading="lazy"
            src={src}
            alt={alt}
            className="object-cover w-full h-72 md:h-56 rounded-t-lg"
        />
    );
};

const ImagePlaceholder = ({className = ''}: { className?: string; }) => {
    return (
        <div className={clsx('flex justify-center h-72 md:h-56 bg-gradient-to-br from-cyan-300 to-violet-400 dark:from-cyan-900 dark:to-violet-700 saturate-[.20] rounded-t-lg', className)}>
            <PhotoSolidIcon className="h-full text-white mix-blend-soft-light"/>
        </div>
    );
};

const HeaderLink = ({href, children}: {href: string; children: ReactNode;}) => {
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
Card.ImagePlaceholder = ImagePlaceholder;
Card.HeaderLink = HeaderLink;
Card.Body = Body;

export default Card;
