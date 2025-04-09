// Toast 
export function useNotifications() {
  const toast = useToast();
  const  showToast = (title : string, desc: string, icon: string, colorV: any)  => {
    toast.add({
      title: title,
      description: desc,
      icon: icon,
      color : colorV,
      closeIcon : 'i-heroicons-x-mark',
      close: {
        color: colorV,
        variant: 'outline',
        class: 'rounded-full'
      }
    })
  }
  return {
    showToast
  };
}
// Image
import { parseURL } from 'ufo';
export const isImageUrlSimple = (url: string): boolean => {
  if (!url) {
    return false;
  }
  const lowercasedUrl = url.toLowerCase();
  return (
    lowercasedUrl.endsWith('.png') ||
    lowercasedUrl.endsWith('.jpg') ||
    lowercasedUrl.endsWith('.jpeg') ||
    lowercasedUrl.endsWith('.gif') ||
    lowercasedUrl.endsWith('.bmp') ||
    lowercasedUrl.endsWith('.webp') ||
    lowercasedUrl.endsWith('.svg')
  );
};
export const checkImageUrl = async (url: string) => {
  const result = ref<boolean | null>(null);
  result.value = await isImageUrl(url);
  return result;
};
export const isImageUrl = (url: string): Promise<boolean> => {
  try {
    const parsedURL = parseURL(url);
    if (!parsedURL.protocol || !parsedURL.host) {
      return Promise.resolve(false);
    }
  } catch (error) {
    return Promise.resolve(false);
  }

  return new Promise((resolve) => {
    const img = new Image();
    img.onload = () => {
      resolve(true);
    };
    img.onerror = () => {
      resolve(false);
    };
    img.src = url;
  });
};