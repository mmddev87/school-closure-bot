from telegram.ext import Application, CommandHandler
from .commands import start
from .commands import set_reminder
from .commands import check

def register_handlers(app: Application)-> None:
    app.add_handler(CommandHandler("start", start.handle))
    app.add_handler(CommandHandler("setreminder", set_reminder.handle))
    app.add_handler(CommandHandler("check", check.handle))