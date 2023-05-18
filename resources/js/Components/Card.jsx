import clsx from 'clsx';
import PhotoSolidIcon from '@/Icons/PhotoSolidIcon';

const Card = ({children, className, ...props}) => {
    return (
        <div
            className={clsx('shadow-md dark:shadow-black/10 bg-white dark:bg-gray-800 sm:rounded-xl', className)}
            {...props}
        >
            {children}
        </div>
    );
};

const Image = ({src, alt}) => {
    return (
        <img
            loading="lazy"
            src={src}
            alt={alt}
            className="object-cover w-full h-72 md:h-56 sm:rounded-t-xl"
        />
    );
};

const ImagePlaceholder = ({className}) => {
    return (
        <div className={clsx('flex justify-center h-72 md:h-56 bg-gradient-to-br from-cyan-300 to-violet-400 dark:from-cyan-900 dark:to-violet-700 saturate-[.20] sm:rounded-t-xl', className)}>
            <PhotoSolidIcon className="h-full text-white mix-blend-soft-light"/>
        </div>
    );
};

const HeaderLink = ({href, children}) => {
    return (
        <a
            href={href}
            className="link-primary inline-block text-xl font-semibold leading-6"
        >
            {children}
        </a>
    );
};

const Body = ({children, className, ...props}) => {
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
