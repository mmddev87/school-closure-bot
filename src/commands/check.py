from telegram import Update
from telegram.ext import ContextTypes

async def handle(update: Update, callback: ContextTypes.DEFAULT_TYPE):
    print('check')