import Card from '@/Components/Card';

export default function LinkListCardContainer({children}) {
    return (
        <Card className="divide-y divide-gray-100 dark:divide-gray-700">
            {children}
        </Card>
    )
}
