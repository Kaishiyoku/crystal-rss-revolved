import React, {CanvasHTMLAttributes, ComponentPropsWithoutRef, useEffect, useRef} from 'react';
import {twMerge} from 'tailwind-merge';
import {PhotoIcon} from '@heroicons/react/24/solid';
import clsx from 'clsx';
import {decode} from 'blurhash';

export function ImageWithBlurHash({blurHash, src, className, ...props}: { blurHash: string | null; } & Omit<ComponentPropsWithoutRef<'img'>, 'loading'>) {
    return (
        <div className={twMerge('relative flex items-center overflow-hidden', className)}>
            <img
                {...props}
                loading="lazy"
                src={src}
                className="z-10 object-contain w-full"
                alt=""
            />

            {blurHash
                ? <BlurhashCanvas hash={blurHash} width={4} height={3} className="!absolute !w-full !h-full"/>
                : <div className="absolute size-full blur-xl" style={{backgroundImage: `url(${src})`}}/>}
        </div>
    );
}

export function ImagePlaceholder({colorIndex = 0, className}: { colorIndex?: number; className?: string; }) {
    const classes = twMerge(
        'flex justify-center bg-gradient-to-br from-cyan-300 to-blue-400 dark:from-cyan-900 dark:to-blue-700 saturate-[.20]',
        clsx({
            'hue-rotate-0': colorIndex === 0,
            'hue-rotate-30': colorIndex === 1,
            'hue-rotate-60': colorIndex === 2,
            'hue-rotate-15': colorIndex === 3,
            'hue-rotate-180': colorIndex === 4,
            'hue-rotate-90': colorIndex === 5,
        }),
        className
    );

    return (
        <div className={classes}>
            <PhotoIcon className="h-full text-white mix-blend-soft-light"/>
        </div>
    );
}

function BlurhashCanvas({hash, width, height, punch = 1, ...props}: { hash: string; width: number; height: number; punch?: number; } & CanvasHTMLAttributes<HTMLCanvasElement>) {
    const canvasElement = useRef<HTMLCanvasElement>(null);

    const draw = () => {
        if (!canvasElement.current) {
            return;
        }

        const pixels = decode(hash, width, height, punch);

        const ctx = canvasElement.current.getContext('2d');

        if (!ctx) {
            return;
        }

        const imageData = ctx.createImageData(width, height);
        imageData.data.set(pixels);
        ctx.putImageData(imageData, 0, 0);
    };

    useEffect(() => {
        draw();
    }, [hash]);

    return (
        <canvas
            ref={canvasElement}
            width={width}
            height={height}
            {...props}
        />
    );
}
