from telegram.ext import Application, CommandHandler
import src.commands.start
import src.commands.set_reminder
import src.commands.check

def register_handlers(app: Application)-> None:
    app.add_handler(CommandHandler("start", src.commands.start.handle))
    app.add_handler(CommandHandler("setreminder", src.commands.set_reminder.handle))
    app.add_handler(CommandHandler("check", src.commands.check.handle))