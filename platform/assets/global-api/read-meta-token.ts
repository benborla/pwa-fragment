export function readMetaToken(): string {
    const meta = document.querySelector<HTMLMetaElement>('meta[http-equiv="Authorization"]');
    return meta ? meta.content : '';
}
