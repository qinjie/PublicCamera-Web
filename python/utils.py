import datetime
import inspect
import logging
import os


def init_logger(name='main'):
    print "Initialize logger"
    logger = logging.getLogger(name)
    log_filename = name + '_log'
    logger.setLevel(logging.WARNING)
    log_formatter = logging.Formatter("%(asctime)s [%(threadName)-12.12s] [%(levelname)-5.5s]  %(message)s")

    if not logger.handlers:
        file_handler = logging.FileHandler("{0}/{1}.txt".format(os.getcwd(), log_filename))
        file_handler.setFormatter(log_formatter)
        logger.addHandler(file_handler)

        console_handler = logging.StreamHandler()
        console_handler.setFormatter(log_formatter)
        logger.addHandler(console_handler)
    return logger


def current_method_name():
    return inspect.stack()[1][3]


def parent_method_name():
    return inspect.stack()[2][3]


def touch(fname, times=None):
    fhandle = open(fname, 'a')
    try:
        os.utime(fname, times)
    finally:
        fhandle.close()


def time_in_range(start, end, x):
    today = datetime.date.today()
    start = datetime.datetime.combine(today, start)
    end = datetime.datetime.combine(today, end)
    x = datetime.datetime.combine(today, x)
    if end <= start:
        end += datetime.timedelta(1)  # tomorrow!
    if x <= start:
        x += datetime.timedelta(1)  # tomorrow!
    return start <= x <= end


def make_sure_path_exist(path):
    try:
        os.makedirs(path)
    except OSError:
        if not os.path.isdir(path):
            raise
