import onDomReady from './utils/onDomReady';
import {scrollTo} from 'scroll-js';
import Zooming from 'zooming';

function adjustNavigationOnScroll(scrollPosition) {
    if (scrollPosition > 50) {
        document.getElementById('navigation').classList.add('md:bg-[#914cd9]', 'md:dark:bg-[#6927ff]');
    } else {
        document.getElementById('navigation').classList.remove('md:bg-[#914cd9]', 'md:dark:bg-[#6927ff]');
    }
}

onDomReady(() => {
    adjustNavigationOnScroll(window.scrollY);

    document.addEventListener('scroll', () => {
        adjustNavigationOnScroll(window.scrollY)
    });

    document.querySelectorAll('[data-scroll]').forEach((element) => {
        element.addEventListener('click', () => {
            const querySelector = element.getAttribute('data-scroll');
            const targetOffset = document.querySelector(querySelector).offsetTop;

            scrollTo(document.body, {top: targetOffset, duration: 750, easing: 'ease-in-out'});
        });
    });

    new Zooming({
        bgColor: '#111111',
    }).listen('[data-provide="zoomable"]');
});
