import { readMetaToken } from './read-meta-token';

beforeEach(() => {
    global.document.head.innerHTML = '';
});

test('read meta token', () => {
    const tokenValue = 'token';
    const metaElement = global.document.createElement('meta');
    metaElement.setAttribute('http-equiv', 'Authorization');
    metaElement.setAttribute('content', tokenValue);
    global.document.head.appendChild(metaElement);
    const token = readMetaToken();
    expect(token).toBe(tokenValue);
});

test('invalid meta element should return empty string', () => {
    const metaElement = global.document.createElement('meta');
    global.document.head.appendChild(metaElement);
    const token = readMetaToken();
    expect(token).toBe('');
});

test('no meta element should return empty string', () => {
    const token = readMetaToken();
    expect(token).toBe('');
});
