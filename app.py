from telegram.ext import ApplicationBuilder
from dotenv import load_dotenv
import os


def main():
    load_dotenv()

    TELEGRAM_BOT_API = os.getenv('TELEGRAM_BOT_API')
    TELEGRAM_FILE_API = os.getenv('TELEGRAM_FILE_API')

    builder = ApplicationBuilder()

    if TELEGRAM_BOT_API:
        builder.base_url(TELEGRAM_BOT_API)
    if TELEGRAM_FILE_API:
        builder.base_file_url(TELEGRAM_FILE_API)
       
    app = builder.token(os.getenv('BOT_TOKEN')).build()

    app.run_polling()



if __name__ == "__main__":
    main()