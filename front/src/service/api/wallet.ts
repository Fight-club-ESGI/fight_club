import { client } from "..";
const namespace = "wallet"

class Wallet {

    async _getWallet() {
        try {
            const uri = `${namespace}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _deposit(amount: string) {
        try {
            const uri = `${namespace}/deposit`;
            const res = await client.post(uri,
                {
                    amount: parseFloat(amount)
                });
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _withdraw(amount: string) {
        try {
            const uri = `${namespace}/withdraw`;
            const res = await client.post(uri,
                {
                    amount: parseFloat(amount)
                }
            );
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const walletService = new Wallet();

export default walletService;