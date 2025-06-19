import {
	type CanvasHTMLAttributes,
	type ComponentPropsWithoutRef,
	useEffect,
	useRef,
} from 'react';
import { twMerge } from 'tailwind-merge';
import { PhotoIcon } from '@heroicons/react/24/solid';
import { decode, isBlurhashValid } from 'blurhash';

export function ImageWithBlurHash({
	blurHash,
	src,
	className,
	...props
}: { blurHash: string | null } & Omit<
	ComponentPropsWithoutRef<'img'>,
	'loading'
>) {
	return (
		<div
			className={twMerge(
				'relative flex items-center overflow-hidden',
				className,
			)}
		>
			<img
				{...props}
				loading="lazy"
				src={src}
				className="z-10 object-contain w-full"
				alt=""
			/>

			{blurHash && isBlurhashValid(blurHash) ? (
				<BlurhashCanvas hash={blurHash} className="absolute! w-full! h-full!" />
			) : (
				<div
					className="absolute size-full blur-xl"
					style={{ backgroundImage: `url(${src})` }}
				/>
			)}
		</div>
	);
}

export function ImagePlaceholder({ className }: { className?: string }) {
	const classes = twMerge(
		'flex justify-center bg-linear-to-br from-zinc-200 to-zinc-300 dark:from-zinc-700 dark:to-zinc-800',
		className,
	);

	return (
		<div className={classes}>
			<PhotoIcon className="h-full text-white mix-blend-soft-light" />
		</div>
	);
}

function BlurhashCanvas({
	hash,
	punch = 1,
	...props
}: { hash: string; punch?: number } & CanvasHTMLAttributes<HTMLCanvasElement>) {
	const canvasElement = useRef<HTMLCanvasElement>(null);

	const width = Number(import.meta.env.VITE_BLURHASH_WIDTH);
	const height = Number(import.meta.env.VITE_BLURHASH_HEIGHT);

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

	// biome-ignore lint/correctness/useExhaustiveDependencies(hash): we need to run this effect when the hash changes
	// biome-ignore lint/correctness/useExhaustiveDependencies(draw): we only need to run this effect when the hash changes
	useEffect(() => {
		draw();
	}, [hash]);

	return (
		<canvas ref={canvasElement} width={width} height={height} {...props} />
	);
}
