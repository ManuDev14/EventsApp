import { useToast as useShadToast } from '@/components/ui/toast';

export const useToast = () => {
    const { toast } = useShadToast();

    return {
        error: (title: string, description?: string) => toast({ title, description, variant: 'destructive' }),
        success: (title: string, description?: string) => toast({ title, description, variant: 'default' }),
    };
};
