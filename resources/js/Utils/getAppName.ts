export default function getAppName(): string
{
    return window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
}
