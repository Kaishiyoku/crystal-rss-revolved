import type { Feed } from '@/types/generated/models';

type ShortFeed = Pick<Feed, 'id' | 'name'>;

export default ShortFeed;
